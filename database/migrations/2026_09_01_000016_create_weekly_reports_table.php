<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_placement_id')->constrained('field_placements')->cascadeOnDelete();
            $table->integer('week_number');
            $table->date('week_start_date');
            $table->date('week_end_date');
            $table->text('activities_completed')->nullable();
            $table->text('skills_acquired')->nullable();
            $table->text('challenges')->nullable();
            $table->text('solutions')->nullable();
            $table->text('summary')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->text('supervisor_comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['field_placement_id', 'week_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
