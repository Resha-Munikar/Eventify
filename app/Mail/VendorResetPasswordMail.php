<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;
    public $userName;

    public function __construct($token, $email, $userName = 'User')
    {
        $this->token = $token;
        $this->email = $email;
        $this->userName = $userName;
    }

    public function build()
    {
        $resetLink = url('/vendor/reset-password/' . $this->token . '?email=' . urlencode($this->email));

        return $this->subject('Reset Your Eventify Password')
                    ->view('emails.vendor-reset-password', [
                        'resetLink' => $resetLink,
                        'email'     => $this->email,
                        'userName'  => $this->userName,
                    ]);
    }
}
