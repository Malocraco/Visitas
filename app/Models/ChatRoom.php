<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'visitor_id',
        'admin_id',
        'subject',
        'status',
        'last_message_at',
        'visitor_online',
        'admin_online',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'visitor_online' => 'boolean',
        'admin_online' => 'boolean',
    ];

    /**
     * Relación con el visitante
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }

    /**
     * Relación con el administrador
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relación con los mensajes
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Obtener el último mensaje
     */
    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'last_message_id');
    }

    /**
     * Obtener mensajes no leídos
     */
    public function unreadMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->where('is_read', false);
    }

    /**
     * Verificar si la sala está activa
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['open', 'active']);
    }

    /**
     * Verificar si la sala está cerrada
     */
    public function isClosed(): bool
    {
        return in_array($this->status, ['closed', 'resolved']);
    }

    /**
     * Marcar como leído
     */
    public function markAsRead(int $userId): void
    {
        $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Obtener el número de mensajes no leídos
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Generar ID único de sala
     */
    public static function generateRoomId(): string
    {
        return 'room_' . uniqid() . '_' . time();
    }
}
