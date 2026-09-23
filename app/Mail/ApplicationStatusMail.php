<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $volunteerName,
        public string $jobTitle,
        public string $organizationName,
        public string $status,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'accepted'
            ? 'Application Accepted'
            : 'Application Update';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}