<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class EmailOtp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'otp_hash',
        'expires_at',
        'attempts',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }

    /**
     * The user this OTP belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the OTP has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the maximum attempt threshold has been reached.
     */
    public function isMaxAttemptsReached(int $max = 5): bool
    {
        return $this->attempts >= $max;
    }

    /**
     * Securely verify submitted plain OTP against stored hash.
     */
    public function verify(string $plainOtp): bool
    {
        return Hash::check($plainOtp, $this->otp_hash);
    }
}
