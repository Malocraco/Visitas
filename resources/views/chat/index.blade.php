@extends('layouts.app')

@section('title', 'Chats - Sistema de Visitas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        Chats
    </h1>
    @if(!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin())
    <a href="{{ route('chat.create') }}" class="btn-primary">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Chat
    </a>
    @endif
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-4 flex items-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4 flex items-center">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Lista de Chats -->
<div class="card">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            Lista de Chats
        </h3>
    </div>
    <div class="overflow-hidden">
        @if($chatRooms->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asunto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visitante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Administrador</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Último Mensaje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensajes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chatRooms as $chatRoom)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $chatRoom->subject }}</span>
                                @if($chatRoom->getUnreadCount(auth()->id()) > 0)
                                    <span class="badge-danger ml-2">{{ $chatRoom->getUnreadCount(auth()->id()) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $chatRoom->visitor->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $chatRoom->visitor->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($chatRoom->admin)
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-success-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $chatRoom->admin->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $chatRoom->admin->email }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($chatRoom->status)
                                @case('open')
                                    <span class="badge-warning">Abierto</span>
                                    @break
                                @case('active')
                                    <span class="badge-primary">Activo</span>
                                    @break
                                @case('closed')
                                    <span class="badge-secondary">Cerrado</span>
                                    @break
                                @case('resolved')
                                    <span class="badge-success">Resuelto</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($chatRoom->last_message_at)
                            <div>
                                <div class="font-medium text-gray-900">{{ $chatRoom->last_message_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $chatRoom->last_message_at->format('H:i') }}</div>
                            </div>
                            @else
                            <span class="text-gray-500">Sin mensajes</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge-info">{{ $chatRoom->messages->count() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <!-- Botón Ver Chat -->
                                <a href="{{ route('chat.show', $chatRoom->room_id) }}" 
                                   class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                <!-- Botón Eliminar Chat (Solo para SuperAdmin) -->
                                @if(auth()->user()->isSuperAdmin())
                                <form action="{{ route('chat.destroy', $chatRoom->room_id) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger delete-chat-btn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay chats disponibles</h3>
            <p class="text-gray-500 mb-6">
                @if(auth()->user()->isVisitor())
                    Los administradores crearán chats cuando necesiten comunicarse contigo sobre tus solicitudes.
                @else
                    Cuando se creen chats, aparecerán aquí.
                @endif
            </p>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <a href="{{ route('chat.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Crear Nuevo Chat
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Paginación -->
@if($chatRooms->hasPages())
<div class="flex justify-center mt-6">
    {{ $chatRooms->links() }}
</div>
@endif

<!-- Modal de confirmación para eliminar chat -->
<div id="delete-chat-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>
        <div class="text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">¿Eliminar chat?</h3>
            <p class="text-sm text-gray-500 mb-6">
                Esta acción no se puede deshacer. El chat y todos sus mensajes se eliminarán permanentemente.
            </p>
            <div class="flex space-x-3 justify-center">
                <button id="cancel-delete-chat" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancelar
                </button>
                <button id="confirm-delete-chat" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('styles')
<style>
/* Estilos para el modal de confirmación */
#delete-chat-modal {
    backdrop-filter: blur(4px);
}

#delete-chat-modal .bg-white {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Actualizar estado online cada 30 segundos
setInterval(function() {
    fetch('{{ route("chat.online-status") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ online: true })
    });
}, 30000);

// Marcar como offline cuando se cierre la página
window.addEventListener('beforeunload', function() {
    fetch('{{ route("chat.online-status") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ online: false })
    });
});

// Modales de confirmación personalizados
let currentForm = null;

// Interceptar solo el botón de eliminar (NO el botón de ver)
document.addEventListener('click', function(e) {
    // Solo interceptar botón de eliminar
    if (e.target.closest('.delete-chat-btn')) {
        e.preventDefault();
        currentForm = e.target.closest('form');
        showModal('delete-chat-modal');
    }
    // NO interceptar enlaces (botón del ojo) - estos van directo al chat
});

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    const cancelBtn = modal.querySelector('[id^="cancel-"]');
    const confirmBtn = modal.querySelector('[id^="confirm-"]');
    
    // Mostrar modal
    modal.classList.remove('hidden');
    
    // Event listeners
    const handleCancel = () => {
        modal.classList.add('hidden');
        currentForm = null;
        cleanup();
    };
    
    const handleConfirm = () => {
        modal.classList.add('hidden');
        if (currentForm) {
            currentForm.submit();
        }
        currentForm = null;
        cleanup();
    };
    
    const cleanup = () => {
        cancelBtn.removeEventListener('click', handleCancel);
        confirmBtn.removeEventListener('click', handleConfirm);
        modal.removeEventListener('click', handleBackdrop);
    };
    
    const handleBackdrop = (e) => {
        if (e.target === modal) {
            handleCancel();
        }
    };
    
    cancelBtn.addEventListener('click', handleCancel);
    confirmBtn.addEventListener('click', handleConfirm);
    modal.addEventListener('click', handleBackdrop);
}
</script>
@endpush
