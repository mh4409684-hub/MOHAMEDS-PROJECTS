<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemControl extends Model
{
    protected $fillable = [
        'is_system_locked',
        'lock_reason',
        'maintenance_mode',
        'owner_name',
        'owner_email',
        'system_license_key',
        'system_announcement',
        'render_service_id',
        'render_api_key',
        'resend_api_key',
        'mail_from_address',
        'mail_from_name',
        'whatsapp_instance_id',
        'whatsapp_token',
    ];

    protected $casts = [
        'is_system_locked' => 'boolean',
        'maintenance_mode' => 'boolean',
    ];

    public static function instance(): self
    {
        return self::firstOrCreate([], [
            'owner_name' => 'MOHAMEDY HAMADI MOHAMED',
            'owner_email' => 'mh4409684@gmail.com',
            'system_license_key' => 'CBE-LIC-MOHAMEDY-2026-X99',
            'is_system_locked' => false,
            'maintenance_mode' => false,
            'lock_reason' => 'Mfumo umezimwa kwa muda na Mwenye Mfumo (MOHAMEDY HAMADI MOHAMED). Wasiliana na Super Admin kupitia mh4409684@gmail.com.',
        ]);
    }
}