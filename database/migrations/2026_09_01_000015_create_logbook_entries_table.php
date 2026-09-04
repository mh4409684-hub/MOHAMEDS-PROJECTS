<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_placement_id')->constrained('field_placements')->cascadeOnDelete();
            $table->date('activity_date');
            $table->text('activity_description');
            $table->text('skills_learned')->nullable();
            $table->text('challenges')->nullable();
            $table->text('solutions')->nullable();
            $table->integer('hours_worked')->default(8);
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->text('supervisor_comments')->nullable();
            $table->text('supervisor_rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_entries');
    }
};
