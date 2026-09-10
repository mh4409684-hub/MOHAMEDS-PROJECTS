<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_otp')->nullable()->after('password');
            $table->timestamp('two_factor_otp_expires_at')->nullable()->after('two_factor_otp');
            $table->string('password_reset_otp')->nullable()->after('two_factor_otp_expires_at');
            $table->timestamp('password_reset_otp_expires_at')->nullable()->after('password_reset_otp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_otp',
                'two_factor_otp_expires_at',
                'password_reset_otp',
                'password_reset_otp_expires_at'
            ]);
        });
    }
};
