<?php

namespace App\Services;

use App\Models\SystemControl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a phone number
     */
    public static function sendMessage(string $phone, string $message): bool
    {
        $control = SystemControl::instance();

        // Format phone to international format: e.g. 0712... -> 255712...
        $cleanedPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanedPhone, '0')) {
            $cleanedPhone = '255' . substr($cleanedPhone, 1);
        } elseif (str_starts_with($cleanedPhone, '+')) {
            $cleanedPhone = substr($cleanedPhone, 1);
        }

        $instanceId = env('WHATSAPP_INSTANCE_ID', $control->whatsapp_instance_id ?? null);
        $token = env('WHATSAPP_TOKEN', $control->whatsapp_token ?? null);

        if (!$instanceId || !$token) {
            Log::info("WhatsApp message simulated to {$cleanedPhone}: {$message}");
            return false;
        }

        try {
            // UltraMsg Gateway API (Standard REST JSON over HTTPS Port 443)
            $response = Http::withoutVerifying()->timeout(3)->asForm()->post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                'token' => $token,
                'to' => $cleanedPhone,
                'body' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp message successfully dispatched to {$cleanedPhone}");
                return true;
            } else {
                Log::warning("WhatsApp dispatch failed: " . $response->body());
                return false;
            }
        } catch (\Throwable $e) {
            Log::error("WhatsApp dispatch exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send password reset OTP via WhatsApp
     */
    public static function sendPasswordResetOtp(string $phone, string $name, string $otp): bool
    {
        $message = "🎓 *COLLEGE OF BUSINESS EDUCATION (CBE)*\n"
                 . "Habari *{$name}*,\n\n"
                 . "Msimbo wako wa kuhakiki na kubadili nenosiri (Password Reset OTP) ni:\n\n"
                 . "👉 *{$otp}*\n\n"
                 . "Msimbo huu unaisha muda wake ndani ya dakika 15.\n"
                 . "Kama hukuomba kubadili nenosiri, tafadhali puuza ujumbe huu.";

        return self::sendMessage($phone, $message);
    }

    /**
     * Send Supervisor account creation details via WhatsApp
     */
    public static function sendSupervisorWelcome(string $phone, string $name, string $email, string $password, string $role = 'Field Supervisor'): bool
    {
        $loginUrl = url('/cbe/login');
        $message = "🎓 *COLLEGE OF BUSINESS EDUCATION (CBE)*\n"
                 . "Habari Msimamizi *{$name}*,\n\n"
                 . "Umesajiliwa rasmi kama *{$role}* kwenye CBE Portal.\n\n"
                 . "📋 *Taarifa za Kuingilia:*\n"
                 . "🔗 Tovuti: {$loginUrl}\n"
                 . "📧 Email: *{$email}*\n"
                 . "🔑 Nenosiri: *{$password}*\n\n"
                 . "Tafadhali ingia kwenye mfumo na ubadilishe nenosiri lako mara ya kwanza.";

        return self::sendMessage($phone, $message);
    }
}
