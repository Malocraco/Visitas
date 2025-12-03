<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Quien envía el mensaje
            $table->enum('sender_type', ['visitor', 'admin']); // Tipo de remitente
            $table->text('message'); // Contenido del mensaje
            $table->string('attachment_path')->nullable(); // Ruta del archivo adjunto
            $table->string('attachment_name')->nullable(); // Nombre del archivo adjunto
            $table->boolean('is_read')->default(false); // Si el mensaje ha sido leído
            $table->timestamp('read_at')->nullable(); // Cuándo fue leído
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['sender_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
