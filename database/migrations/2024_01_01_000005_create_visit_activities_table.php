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
        Schema::create('visit_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('instructor')->nullable(); // Instructor name
            $table->integer('duration_minutes')->default(60); // Duration in minutes
            $table->integer('max_participants')->nullable(); // Maximum participants allowed
            $table->boolean('is_active')->default(true);
            $table->text('requirements')->nullable(); // Special requirements (boots, lab coats, etc.)
            $table->text('location')->nullable(); // Where the activity takes place
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_activities');
    }
};
