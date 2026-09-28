<?php

namespace App\Mail;

use App\Models\User;
use App\Models\VendorKyc;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorKycApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public VendorKyc $kyc;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, VendorKyc $kyc)
    {
        $this->user = $user;
        $this->kyc = $kyc;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Congratulations! Your Eventify KYC Verification is Approved')
                    ->view('emails.vendor-kyc-approved');
    }
}
