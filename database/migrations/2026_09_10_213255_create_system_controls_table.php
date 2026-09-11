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
        Schema::create('system_controls', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_system_locked')->default(false); // Server Kill Switch (Only Mohamedy can toggle)
            $table->string('lock_reason')->nullable()->default('Mfumo umezimwa kwa muda na Mwenye Mfumo (Super Admin). Tafadhali wasiliana na Msimamizi Mkuu.');
            $table->boolean('maintenance_mode')->default(false);
            $table->string('owner_name')->default('MOHAMEDY HAMADI MOHAMED');
            $table->string('owner_email')->default('mh4409684@gmail.com');
            $table->string('system_license_key')->default('CBE-LIC-MOHAMEDY-2026-X99');
            $table->text('system_announcement')->nullable();
            $table->string('render_service_id')->nullable();
            $table->string('render_api_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_controls');
    }
};
