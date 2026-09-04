<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_supervisor_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_placement_id')->constrained('field_placements')->cascadeOnDelete();
            $table->foreignId('supervisor_staff_id')->constrained('staff')->cascadeOnDelete();
            $table->date('assigned_date');
            $table->date('unassigned_date')->nullable();
            $table->string('status')->default('active'); // active, completed, transferred
            $table->timestamps();

            $table->unique(['field_placement_id', 'supervisor_staff_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_supervisor_assignments');
    }
};
