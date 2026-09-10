<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollegeSecurityMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $subjectText;
    public string $code;
    public string $actionType;

    public function __construct(User $user, string $subjectText, string $code, string $actionType = '2fa')
    {
        $this->user = $user;
        $this->subjectText = $subjectText;
        $this->code = $code;
        $this->actionType = $actionType;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.security-code',
        );
    }
}
