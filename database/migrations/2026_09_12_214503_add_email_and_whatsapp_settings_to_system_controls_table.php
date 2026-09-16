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
        Schema::table('system_controls', function (Blueprint $table) {
            $table->string('resend_api_key')->nullable()->after('render_api_key');
            $table->string('mail_from_address')->nullable()->default('onboarding@resend.dev')->after('resend_api_key');
            $table->string('mail_from_name')->nullable()->default('CBE Field Portal')->after('mail_from_address');
            $table->string('whatsapp_instance_id')->nullable()->after('mail_from_name');
            $table->string('whatsapp_token')->nullable()->after('whatsapp_instance_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_controls', function (Blueprint $table) {
            $table->dropColumn([
                'resend_api_key',
                'mail_from_address',
                'mail_from_name',
                'whatsapp_instance_id',
                'whatsapp_token',
            ]);
        });
    }
};
