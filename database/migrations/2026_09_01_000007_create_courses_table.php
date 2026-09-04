<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->integer('credit_hours')->nullable();
            $table->integer('year_level'); // 1, 2, 3, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['code', 'programme_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
