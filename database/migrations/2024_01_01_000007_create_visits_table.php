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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Visitante que solicita
            $table->string('institution_name'); // Nombre de la institución/empresa
            $table->string('contact_person'); // Persona de contacto
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('contact_position')->nullable(); // Cargo de la persona de contacto
            $table->text('institution_description')->nullable(); // Descripción de la institución
            $table->string('institution_type'); // Universidad, Empresa, Colegio, etc.
            $table->integer('expected_participants'); // Número de participantes esperados
            $table->date('preferred_date');
            $table->time('preferred_start_time');
            $table->time('preferred_end_time');
            $table->text('visit_purpose'); // Propósito de la visita
            $table->text('special_requirements')->nullable(); // Requisitos especiales
            $table->enum('status', [
                'pending',      // Pendiente de revisión
                'under_review', // En revisión/negociación
                'approved',     // Aprobada
                'rejected',     // Rechazada
                'completed',    // Completada
                'cancelled'     // Cancelada
            ])->default('pending');
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->date('confirmed_date')->nullable(); // Fecha confirmada después de negociación
            $table->time('confirmed_start_time')->nullable();
            $table->time('confirmed_end_time')->nullable();
            $table->text('admin_notes')->nullable(); // Notas del administrador
            $table->text('rejection_reason')->nullable(); // Razón del rechazo si aplica
            $table->boolean('restaurant_service')->default(false); // Si requiere servicio de restaurante
            $table->integer('restaurant_participants')->nullable(); // Número de personas para restaurante
            $table->text('restaurant_notes')->nullable(); // Notas especiales para restaurante
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
