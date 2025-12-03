<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM para incluir 'postponed'
        DB::statement("ALTER TABLE visits MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected', 'postponed', 'completed', 'cancelled') DEFAULT 'pending'");
        
        // Agregar columnas para el posponimiento
        Schema::table('visits', function (Blueprint $table) {
            $table->timestamp('postponed_at')->nullable();
            $table->foreignId('postponed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('postponement_reason')->nullable();
            $table->date('suggested_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir el ENUM a su estado original
        DB::statement("ALTER TABLE visits MODIFY COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected', 'completed', 'cancelled') DEFAULT 'pending'");
        
        // Eliminar las columnas agregadas
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn(['postponed_at', 'postponed_by', 'postponement_reason', 'suggested_date']);
        });
    }
};
