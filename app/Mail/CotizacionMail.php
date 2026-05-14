<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CotizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Cotizacion $cotizacion
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cotización ' . $this->cotizacion->codigo . ' — Rayo Verde',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cotizacion',
            with: [
                'cotizacion' => $this->cotizacion,
            ]
        );
    }

    public function attachments(): array
    {
        // Genera el PDF en memoria y lo adjunta sin guardar en disco
        $pdf = Pdf::loadView('pdf.cotizacion', [
            'cotizacion' => $this->cotizacion
        ]);

        return [
            Attachment::fromData(
                fn() => $pdf->output(),
                'cotizacion_' . $this->cotizacion->codigo . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}