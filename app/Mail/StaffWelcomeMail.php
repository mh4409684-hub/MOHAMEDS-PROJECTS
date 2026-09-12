<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $temporaryPassword;
    public string $staffType;
    public string $loginUrl;

    public function __construct(User $user, string $temporaryPassword, string $staffType)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->staffType = $staffType;
        $this->loginUrl = url('/cbe/login');
    }

    public function envelope(): Envelope
    {
        $roleTitle = match($this->staffType) {
            'supervisor', 'field_supervisor' => 'Msimamizi wa Field (Field Supervisor)',
            'lecturer' => 'Mkufunzi (Lecturer)',
            'coordinator', 'field_coordinator' => 'Mratibu wa Field (Coordinator)',
            default => 'Mwanachama wa CBE Staff',
        };

        return new Envelope(
            subject: "CBE Portal - Taarifa ya Kufunguliwa Akaunti: {$roleTitle}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-welcome',
        );
    }
}
