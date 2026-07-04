<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactConfirmation extends Mailable implements ShouldQueue
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
        return new Envelope(
            subject: 'Recibimos tu mensaje · ' . config('taquerosweb.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact-confirmation',
            with: [
                'data' => $this->data,
                'whatsapp' => \App\Support\Site::whatsappUrl(),
            ],
        );
    }
}
