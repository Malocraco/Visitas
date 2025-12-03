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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_id')->unique(); // ID único de la sala
            $table->foreignId('visitor_id')->constrained('users')->onDelete('cascade'); // Visitante
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // Administrador asignado
            $table->string('subject')->nullable(); // Asunto del chat
            $table->enum('status', ['open', 'active', 'closed', 'resolved'])->default('open'); // Estado del chat
            $table->timestamp('last_message_at')->nullable(); // Último mensaje
            $table->boolean('visitor_online')->default(false); // Si el visitante está online
            $table->boolean('admin_online')->default(false); // Si el admin está online
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['status', 'last_message_at']);
            $table->index(['visitor_id', 'admin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
