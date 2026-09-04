<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_entry_id')->nullable()->constrained('logbook_entries')->cascadeOnDelete();
            $table->foreignId('weekly_report_id')->nullable()->constrained('weekly_reports')->cascadeOnDelete();
            $table->foreignId('supervisor_staff_id')->constrained('staff')->cascadeOnDelete();
            $table->string('review_type'); // logbook_entry, weekly_report
            $table->string('decision'); // approved, rejected
            $table->text('comments')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('digital_signature')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisor_reviews');
    }
};
