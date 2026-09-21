<?php

namespace App\Services;

use App\Mail\EmailOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailOtpService
{
    public const OTP_LENGTH = 6;
    public const EXPIRY_MINUTES = 5;
    public const MAX_ATTEMPTS = 5;
    public const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * Generate a new 6-digit OTP, store hashed in database, and send via email.
     *
     * @param User $user
     * @return array
     */
    public function generateAndSend(User $user): array
    {
        // 1. Invalidate any existing OTP records for this user
        EmailOtp::where('user_id', $user->id)->delete();

        // 2. Cryptographically secure 6-digit OTP generation
        $otp = (string) random_int(100000, 999999);

        // 3. Securely hash the OTP before storing
        $otpHash = Hash::make($otp);

        // 4. Store hashed OTP with 5-minute expiry
        $otpRecord = EmailOtp::create([
            'user_id' => $user->id,
            'otp_hash' => $otpHash,
            'expires_at' => Carbon::now()->addMinutes(self::EXPIRY_MINUTES),
            'attempts' => 0,
        ]);

        // 5. Send OTP to user email without logging plaintext OTP
        try {
            Mail::to($user->email)->send(new EmailOtpMail($otp, $user->name));
            return [
                'success' => true,
                'message' => 'Verification code sent to your email.',
            ];
        } catch (\Throwable $e) {
            Log::error('Failed to send OTP email to user ID ' . $user->id . ': ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Unable to send verification email. Please check your connection and click Resend Code.',
            ];
        }
    }

    /**
     * Check whether the user is allowed to request a resend (rate limit cooldown).
     *
     * @param User $user
     * @return bool
     */
    public function canResend(User $user): bool
    {
        return $this->getCooldownRemaining($user) <= 0;
    }

    /**
     * Get remaining cooldown seconds before a resend is permitted.
     *
     * @param User $user
     * @return int
     */
    public function getCooldownRemaining(User $user): int
    {
        $latestOtp = EmailOtp::where('user_id', $user->id)->latest()->first();
        if (!$latestOtp) {
            return 0;
        }

        $secondsSinceCreation = (int) Carbon::now()->diffInSeconds($latestOtp->created_at, true);
        $remaining = self::RESEND_COOLDOWN_SECONDS - $secondsSinceCreation;

        return max(0, $remaining);
    }

    /**
     * Verify a submitted 6-digit OTP code against the stored hash.
     *
     * @param User $user
     * @param string $plainOtp
     * @return array
     */
    public function verify(User $user, string $plainOtp): array
    {
        // 1. Check if user is already verified
        if ($user->email_verified_at !== null) {
            return [
                'status' => 'already_verified',
                'success' => true,
                'message' => 'Your email is already verified.',
            ];
        }

        // 2. Find active OTP record
        $otpRecord = EmailOtp::where('user_id', $user->id)->first();
        if (!$otpRecord) {
            return [
                'status' => 'not_found',
                'success' => false,
                'message' => 'No active verification code found. Please request a new code.',
            ];
        }

        // 3. Check attempt limit
        if ($otpRecord->isMaxAttemptsReached(self::MAX_ATTEMPTS)) {
            $otpRecord->delete();
            return [
                'status' => 'max_attempts',
                'success' => false,
                'message' => 'Too many incorrect attempts. Please request a new verification code.',
            ];
        }

        // 4. Check expiration
        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            return [
                'status' => 'expired',
                'success' => false,
                'message' => 'This verification code has expired. Please request a new code.',
            ];
        }

        // 5. Compare submitted OTP against stored hash
        if (!$otpRecord->verify(trim($plainOtp))) {
            $otpRecord->increment('attempts');
            $remaining = max(0, self::MAX_ATTEMPTS - $otpRecord->attempts);

            if ($remaining === 0) {
                $otpRecord->delete();
                return [
                    'status' => 'max_attempts',
                    'success' => false,
                    'message' => 'Too many incorrect attempts. Please request a new verification code.',
                ];
            }

            return [
                'status' => 'invalid',
                'success' => false,
                'message' => "Invalid verification code. {$remaining} attempt(s) remaining.",
            ];
        }

        // 6. Successful verification: Mark email verified and delete OTP record immediately
        $user->email_verified_at = Carbon::now();
        $user->save();

        $otpRecord->delete();

        return [
            'status' => 'success',
            'success' => true,
            'message' => 'Your email has been verified successfully.',
        ];
    }
}
