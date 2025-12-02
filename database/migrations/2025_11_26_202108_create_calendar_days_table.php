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
        Schema::create('calendar_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Fecha del día
            $table->enum('status', ['available', 'unavailable', 'scheduled'])->default('available');
            // Para días agendados
            $table->string('institution_name')->nullable(); // Nombre de universidad, empresa, colegio, etc.
            $table->time('entry_time')->nullable(); // Hora de entrada
            $table->time('exit_time')->nullable(); // Hora de salida
            $table->text('notes')->nullable(); // Notas adicionales
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Quien creó/editó el día
            $table->timestamps();
            
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_days');
    }
};
