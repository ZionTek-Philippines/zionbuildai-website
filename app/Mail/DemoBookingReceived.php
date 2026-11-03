<?php

namespace App\Mail;

use App\Models\DemoBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the sales inbox whenever someone books a demo on the homepage.
 */
class DemoBookingReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public DemoBooking $booking) {}

    /**
     * Get the message envelope. Replies go straight to the visitor when they left an email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New demo booking: {$this->booking->name}",
            replyTo: $this->booking->email ? [new Address($this->booking->email, $this->booking->name)] : [],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.demo-booking-received',
        );
    }
}
