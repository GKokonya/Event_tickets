<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $body;
    public $subject;
    public $ticket_details;
    /**
     * Create a new message instance.
     */
    public function __construct($body,$subject,$ticket_details)
    {
        //
        $this->subject = $subject;
        $this->ticket_details =$ticket_details;
        $this->body = $body;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'ticket_body',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
       $pdf = Pdf::loadView('ticket',['ticket_details' => $this->ticket_details]);
        return [
            Attachment::fromData(fn ()=>$pdf->output(), 'Ticket.pdf')->withMime('application/pdf')
        ];
    }
}
