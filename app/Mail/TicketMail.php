<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $tickets;
    public $user;
    public $ticketType;
    public $booking;

    public function __construct($user, $event, $tickets, $ticketType = null, $booking = null)
    {
        $this->user = $user;
        $this->event = $event;
        $this->tickets = $tickets;
        $this->ticketType = $ticketType;
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Your Event Ticket - ' . ($this->event->event_name ?? 'Eventify'))
                    ->view('emails.ticket');
    }
}
