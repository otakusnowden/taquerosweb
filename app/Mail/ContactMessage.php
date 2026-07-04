<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  array{nombre:string,restaurante:?string,email:string,telefono:?string,mensaje:?string}  $data
     */
    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        $restaurante = $this->data['restaurante'] ?? null;
        $subject = 'Nuevo contacto'
            . ($restaurante ? " — {$restaurante}" : '')
            . ' · ' . $this->data['nombre'];

        return new Envelope(
            subject: $subject,
            // Reply directly to the visitor when hitting "Responder".
            replyTo: [new Address($this->data['email'], $this->data['nombre'])],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-message',
            with: ['data' => $this->data],
        );
    }
}
