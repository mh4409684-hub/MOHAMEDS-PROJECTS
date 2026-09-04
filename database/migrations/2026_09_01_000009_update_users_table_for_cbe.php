<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('registration_number')->unique()->nullable()->after('username');
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->nullOnDelete()->after('registration_number');
            $table->string('phone')->nullable()->after('campus_id');
            $table->string('profile_picture')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('profile_picture');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_username_unique');
            $table->dropUnique('users_registration_number_unique');
            $table->dropForeign('users_campus_id_foreign');
            $table->dropColumn(['username', 'registration_number', 'campus_id', 'phone', 'profile_picture', 'is_active', 'last_login_at']);
        });
    }
};
