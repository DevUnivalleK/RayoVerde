<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ReporteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $usuario,
        public string $pdfContent
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte General — Rayo Verde · ' . now()->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reporte',
            with: ['usuario' => $this->usuario]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn() => $this->pdfContent,
                'reporte_' . now()->format('Y-m-d') . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}