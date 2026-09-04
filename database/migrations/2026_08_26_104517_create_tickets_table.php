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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
    $table->string('title', 200);
    $table->text('description');
    $table->foreignId('category_id');
    $table->string('priority');
    $table->foreignId('requester_id');
    $table->string('status')->default('new');
    $table->string('source')->default('portal');
    $table->foreignId('department_id')->nullable();
    $table->foreignId('sla_policy_id')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
