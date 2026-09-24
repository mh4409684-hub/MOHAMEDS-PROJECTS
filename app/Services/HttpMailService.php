<?php

namespace App\Services;

use App\Models\SystemControl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HttpMailService
{
    /**
     * Send an email reliably via Resend HTTPS API (Port 443) or Laravel Mail
     */
    public static function send(string $to, string $subject, string $htmlContent, ?string $plainText = null): bool
    {
        $control = SystemControl::instance();

        $apiKey = env('RESEND_API_KEY', $control->resend_api_key ?? null);
        $fromEmail = env('MAIL_FROM_ADDRESS', $control->mail_from_address ?: 'onboarding@resend.dev');
        $fromName = env('MAIL_FROM_NAME', $control->mail_from_name ?: 'CBE Field Portal');

        // 1. If Resend API Key is configured, use HTTPS port 443 (100% reliable on Render)
        if (!empty($apiKey)) {
            try {
                $payload = [
                    'from' => "{$fromName} <{$fromEmail}>",
                    'to' => [$to],
                    'subject' => $subject,
                    'html' => $htmlContent,
                ];

                if (!empty($plainText)) {
                    $payload['text'] = $plainText;
                }

                $response = Http::timeout(3)
                    ->withToken($apiKey)
                    ->post('https://api.resend.com/emails', $payload);

                if ($response->successful()) {
                    Log::info("Email successfully dispatched via Resend HTTPS API to {$to}");
                    return true;
                }

                Log::warning("Resend API response error (" . $response->status() . "): " . $response->body());
            } catch (\Throwable $e) {
                Log::error("Resend API dispatch exception: " . $e->getMessage());
            }
        }

        // 2. Fallback to standard Laravel Mailer
        // Fast probe: check if outbound SMTP port is reachable to prevent 60-second freezes on cloud hosts (e.g. Render blocks ports 25, 465, 587)
        $defaultMailer = config('mail.default');
        if ($defaultMailer === 'smtp') {
            $smtpHost = config('mail.mailers.smtp.host');
            $smtpPort = (int) config('mail.mailers.smtp.port', 587);

            $probe = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 1.0);
            if (!$probe) {
                Log::info("Outbound SMTP to {$smtpHost}:{$smtpPort} is unavailable or blocked by hosting firewall ({$errstr}). Skipping blocking mailer attempt.");
                return false;
            }
            fclose($probe);
        }

        try {
            Mail::html($htmlContent, function ($message) use ($to, $subject, $fromEmail, $fromName) {
                $message->to($to)
                    ->subject($subject);
                if (!empty($fromEmail)) {
                    $message->from($fromEmail, $fromName);
                }
            });

            Log::info("Email dispatched via Laravel Mailer to {$to}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Laravel Mailer dispatch failed: " . $e->getMessage());
            return false;
        }
    }
}
