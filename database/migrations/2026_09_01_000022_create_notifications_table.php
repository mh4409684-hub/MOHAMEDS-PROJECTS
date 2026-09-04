<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // logbook_submitted, logbook_approved, logbook_rejected, attendance_open, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable();
            $table->foreignId('related_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('related_field_placement_id')->nullable()->constrained('field_placements')->nullOnDelete();
            $table->foreignId('related_logbook_id')->nullable()->constrained('logbook_entries')->nullOnDelete();
            $table->foreignId('related_class_session_id')->nullable()->constrained('class_sessions')->nullOnDelete();
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
