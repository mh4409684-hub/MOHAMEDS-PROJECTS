<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->foreignId('campus_id')->constrained('campuses')->cascadeOnDelete();
            $table->string('name'); // e.g., "BIT Year 1 - Section A"
            $table->string('code')->unique(); // e.g., "BIT-Y1-A"
            $table->integer('year_level'); // 1, 2, 3
            $table->string('section_letter'); // A, B, C, etc.
            $table->integer('capacity')->nullable(); // Maximum students
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['programme_id', 'campus_id', 'year_level', 'section_letter']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
