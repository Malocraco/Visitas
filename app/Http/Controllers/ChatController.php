<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Mostrar la lista de chats para visitantes
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // Para administradores: mostrar todas las salas de chat
            $chatRooms = ChatRoom::with(['visitor', 'admin', 'messages'])
                ->orderBy('id', 'asc')
                ->paginate(15);
        } else {
            // Para visitantes: mostrar solo sus chats
            $chatRooms = ChatRoom::with(['visitor', 'admin', 'messages'])
                ->where('visitor_id', $user->id)
                ->orderBy('id', 'asc')
                ->paginate(15);
        }
        
        return view('chat.index', compact('chatRooms'));
    }

    /**
     * Mostrar una sala de chat específica
     */
    public function show($roomId)
    {
        $chatRoom = ChatRoom::with(['visitor', 'admin', 'messages.sender'])
            ->where('room_id', $roomId)
            ->firstOrFail();
        
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $chatRoom->visitor_id !== $user->id) {
            abort(403, 'No tienes permisos para acceder a este chat.');
        }
        
        // Marcar mensajes como leídos
        $chatRoom->markAsRead($user->id);
        
        // Si es un admin y la sala no tiene admin asignado, asignarse
        if (($user->isSuperAdmin() || $user->isAdmin()) && !$chatRoom->admin_id) {
            $chatRoom->update(['admin_id' => $user->id, 'status' => 'active']);
        }
        
        return view('chat.show', compact('chatRoom'));
    }

    /**
     * Crear una nueva sala de chat
     */
    public function create()
    {
        $user = Auth::user();
        
        // Solo visitantes pueden crear chats
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('chat.index')
                ->with('error', 'Los administradores no pueden crear chats.');
        }
        
        return view('chat.create');
    }

    /**
     * Guardar una nueva sala de chat
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Solo visitantes pueden crear chats
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('chat.index')
                ->with('error', 'Los administradores no pueden crear chats.');
        }
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        
        // Crear la sala de chat
        $chatRoom = ChatRoom::create([
            'room_id' => ChatRoom::generateRoomId(),
            'visitor_id' => $user->id,
            'subject' => $request->subject,
            'status' => 'open',
            'last_message_at' => now(),
        ]);
        
        // Crear el primer mensaje
        $chatRoom->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => 'visitor',
            'message' => $request->message,
        ]);
        
        return redirect()->route('chat.show', $chatRoom->room_id)
            ->with('success', 'Chat creado exitosamente. Un administrador se pondrá en contacto contigo pronto.');
    }

    /**
     * Enviar un mensaje
     */
    public function sendMessage(Request $request, $roomId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // Máximo 10MB
        ]);
        
        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $chatRoom->visitor_id !== $user->id) {
            return response()->json(['error' => 'No tienes permisos para enviar mensajes en este chat.'], 403);
        }
        
        // Determinar el tipo de remitente
        $senderType = ($user->isSuperAdmin() || $user->isAdmin()) ? 'admin' : 'visitor';
        
        // Procesar archivo adjunto si existe
        $attachmentPath = null;
        $attachmentName = null;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('chat-attachments', 'public');
        }
        
        // Crear el mensaje
        $message = $chatRoom->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);
        
        // Actualizar la sala de chat
        $chatRoom->update([
            'last_message_at' => now(),
            'status' => 'active',
        ]);
        
        // Cargar las relaciones para la respuesta
        $message->load('sender');
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'formatted_time' => $message->getFormattedTime(),
            'time_ago' => $message->getTimeAgo(),
        ]);
    }

    /**
     * Obtener mensajes de una sala (para AJAX)
     */
    public function getMessages($roomId)
    {
        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $chatRoom->visitor_id !== $user->id) {
            return response()->json(['error' => 'No tienes permisos para acceder a este chat.'], 403);
        }
        
        $messages = $chatRoom->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Marcar como leídos
        $chatRoom->markAsRead($user->id);
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
            'unread_count' => $chatRoom->getUnreadCount($user->id),
        ]);
    }

    /**
     * Cerrar una sala de chat
     */
    public function close($roomId)
    {
        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Solo administradores pueden cerrar chats
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            return redirect()->route('chat.show', $roomId)
                ->with('error', 'Solo los administradores pueden cerrar chats.');
        }
        
        $chatRoom->update(['status' => 'closed']);
        
        return redirect()->route('chat.index')
            ->with('success', 'Chat cerrado exitosamente.');
    }

    /**
     * Marcar chat como resuelto
     */
    public function resolve($roomId)
    {
        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Solo administradores pueden marcar como resuelto
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            return redirect()->route('chat.show', $roomId)
                ->with('error', 'Solo los administradores pueden marcar chats como resueltos.');
        }
        
        $chatRoom->update(['status' => 'resolved']);
        
        return redirect()->route('chat.index')
            ->with('success', 'Chat marcado como resuelto exitosamente.');
    }

    /**
     * Actualizar estado online/offline
     */
    public function updateOnlineStatus(Request $request)
    {
        $user = Auth::user();
        $isOnline = $request->input('online', false);
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // Actualizar estado de admin en todas las salas donde participe
            ChatRoom::where('admin_id', $user->id)
                ->update(['admin_online' => $isOnline]);
        } else {
            // Actualizar estado de visitante en sus salas
            ChatRoom::where('visitor_id', $user->id)
                ->update(['visitor_online' => $isOnline]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Obtener estadísticas de chat (para administradores)
     */
    public function statistics()
    {
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'No tienes permisos para ver estadísticas.');
        }
        
        $stats = [
            'total_rooms' => ChatRoom::count(),
            'open_rooms' => ChatRoom::where('status', 'open')->count(),
            'active_rooms' => ChatRoom::where('status', 'active')->count(),
            'closed_rooms' => ChatRoom::where('status', 'closed')->count(),
            'resolved_rooms' => ChatRoom::where('status', 'resolved')->count(),
            'total_messages' => ChatMessage::count(),
            'unread_messages' => ChatMessage::where('is_read', false)->count(),
        ];
        
        return view('chat.statistics', compact('stats'));
    }

    /**
     * Obtener notificaciones de chat en tiempo real
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // Para administradores: chats sin asignar y mensajes no leídos
            $unassignedRooms = ChatRoom::where('admin_id', null)
                ->where('status', 'open')
                ->count();
                
            $unreadMessages = ChatMessage::whereHas('chatRoom', function($query) use ($user) {
                $query->where('admin_id', $user->id);
            })->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
        } else {
            // Para visitantes: mensajes no leídos en sus chats
            $unreadMessages = ChatMessage::whereHas('chatRoom', function($query) use ($user) {
                $query->where('visitor_id', $user->id);
            })->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
            
            $unassignedRooms = 0;
        }
        
        return response()->json([
            'unread_messages' => $unreadMessages,
            'unassigned_rooms' => $unassignedRooms,
            'total_notifications' => $unreadMessages + $unassignedRooms
        ]);
    }

    /**
     * Crear chat automático desde el sistema (para posponimientos, etc.)
     */
    public function createSystemChat($visitorId, $adminId, $subject, $initialMessage)
    {
        $chatRoom = ChatRoom::create([
            'room_id' => ChatRoom::generateRoomId(),
            'visitor_id' => $visitorId,
            'admin_id' => $adminId,
            'subject' => $subject,
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        // Crear mensaje inicial del sistema
        $chatRoom->messages()->create([
            'sender_id' => $adminId,
            'sender_type' => 'admin',
            'message' => $initialMessage,
        ]);

        return $chatRoom;
    }

    /**
     * Editar un mensaje
     */
    public function editMessage(Request $request, $roomId, $messageId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $chatRoom->visitor_id !== $user->id) {
            return response()->json(['error' => 'No tienes permisos para editar mensajes en este chat.'], 403);
        }

        $message = $chatRoom->messages()->findOrFail($messageId);
        
        // Solo el autor del mensaje puede editarlo
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'Solo puedes editar tus propios mensajes.'], 403);
        }

        // Actualizar el mensaje
        $message->update([
            'message' => $request->message,
            'updated_at' => now(),
        ]);

        // Cargar las relaciones para la respuesta
        $message->load('sender');

        return response()->json([
            'success' => true,
            'message' => $message,
            'formatted_time' => $message->getFormattedTime(),
        ]);
    }

    /**
     * Eliminar un mensaje
     */
    public function deleteMessage($roomId, $messageId)
    {
        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user->isSuperAdmin() && !$user->isAdmin() && $chatRoom->visitor_id !== $user->id) {
            return response()->json(['error' => 'No tienes permisos para eliminar mensajes en este chat.'], 403);
        }

        $message = $chatRoom->messages()->findOrFail($messageId);
        
        // Solo el autor del mensaje puede eliminarlo
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'Solo puedes eliminar tus propios mensajes.'], 403);
        }

        // Eliminar el mensaje
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mensaje eliminado exitosamente.',
        ]);
    }

    /**
     * Eliminar un chat completo (solo superadministradores)
     */
    public function destroy($roomId)
    {
        $chatRoom = ChatRoom::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();
        
        // Solo superadministradores pueden eliminar chats
        if (!$user->isSuperAdmin()) {
            return redirect()->route('chat.index')
                ->with('error', 'Solo los superadministradores pueden eliminar chats.');
        }

        // Eliminar todos los mensajes del chat
        $chatRoom->messages()->delete();
        
        // Eliminar el chat
        $chatRoom->delete();

        return redirect()->route('chat.index')
            ->with('success', 'Chat eliminado exitosamente.');
    }
}
