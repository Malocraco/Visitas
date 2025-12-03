<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'sender_type',
        'message',
        'attachment_path',
        'attachment_name',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relación con la sala de chat
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Relación con el remitente
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Verificar si el mensaje es del visitante
     */
    public function isFromVisitor(): bool
    {
        return $this->sender_type === 'visitor';
    }

    /**
     * Verificar si el mensaje es del administrador
     */
    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }

    /**
     * Verificar si el mensaje tiene archivo adjunto
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Obtener la URL del archivo adjunto
     */
    public function getAttachmentUrl(): ?string
    {
        if ($this->hasAttachment()) {
            return asset('storage/' . $this->attachment_path);
        }
        return null;
    }

    /**
     * Marcar como leído
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Obtener el tiempo transcurrido desde el mensaje
     */
    public function getTimeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Obtener la hora formateada del mensaje
     */
    public function getFormattedTime(): string
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Obtener la fecha formateada del mensaje
     */
    public function getFormattedDate(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}
