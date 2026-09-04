<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_placement_id')->constrained('field_placements')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('status'); // present, absent, late, excused
            $table->string('latitude')->nullable(); // GPS location for verification
            $table->string('longitude')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['field_placement_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_attendance');
    }
};
