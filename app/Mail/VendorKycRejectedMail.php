<?php

namespace App\Mail;

use App\Models\User;
use App\Models\VendorKyc;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorKycRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public VendorKyc $kyc;
    public string $reason;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, VendorKyc $kyc, string $reason)
    {
        $this->user = $user;
        $this->kyc = $kyc;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Action Required: Eventify KYC Verification Update')
                    ->view('emails.vendor-kyc-rejected');
    }
}
