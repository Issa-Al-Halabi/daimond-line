<?php

namespace App\Mail;

use Hyvikk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyTestMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $msg;
    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // return $this->from(Hyvikk::get("email"))->subject('Service Reminder');
        // return $this->from(Hyvikk::get("email"))->subject('Ride Booked. Booking ID: ')->markdown('emails.otp');
        return $this->subject('')->markdown('emails.otp');
    }
}
