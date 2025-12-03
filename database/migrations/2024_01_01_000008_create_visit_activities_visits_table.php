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
        Schema::create('visit_activities_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('visit_activity_id')->constrained()->onDelete('cascade');
            $table->integer('participants')->default(0); // Número de participantes para esta actividad
            $table->text('notes')->nullable(); // Notas específicas para esta actividad
            $table->timestamps();
            
            $table->unique(['visit_id', 'visit_activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_activities_visits');
    }
};
