<?php

namespace Tests\Feature;

use App\Models\EmailOtp;
use App\Models\User;
use App\Services\EmailOtpService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailOtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_registration_creates_unverified_user_generates_hashed_otp_and_redirects_to_verification()
    {
        $uniqueEmail = 'customer_' . uniqid() . '@example.com';

        $response = $this->post(route('register'), [
            'name' => 'John Customer',
            'email' => $uniqueEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        $otpRecord = EmailOtp::where('user_id', $user->id)->first();
        $this->assertNotNull($otpRecord);
        $this->assertNotEquals(6, strlen($otpRecord->otp_hash)); // Confirm hashed, not plain
        $this->assertTrue($otpRecord->expires_at->isFuture());
    }

    public function test_correct_otp_verifies_email_and_deletes_otp_record()
    {
        $user = User::create([
            'name' => 'Jane Test',
            'email' => 'jane_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'user',
        ]);

        $plainOtp = '123456';
        EmailOtp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($plainOtp),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('verification.verify'), [
            'otp' => $plainOtp,
        ]);

        $response->assertRedirect(route('welcome'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        $this->assertNull(EmailOtp::where('user_id', $user->id)->first());
    }

    public function test_incorrect_otp_increments_attempts()
    {
        $user = User::create([
            'name' => 'Attempt Test',
            'email' => 'attempt_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'user',
        ]);

        $plainOtp = '654321';
        $otpRecord = EmailOtp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($plainOtp),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('verification.verify'), [
            'otp' => '111111',
        ]);

        $response->assertSessionHasErrors('otp');

        $otpRecord->refresh();
        $this->assertEquals(1, $otpRecord->attempts);
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_exceeding_maximum_attempts_invalidates_otp()
    {
        $user = User::create([
            'name' => 'Max Attempts',
            'email' => 'max_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'user',
        ]);

        $otpRecord = EmailOtp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make('888888'),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 4,
        ]);

        $response = $this->actingAs($user)->post(route('verification.verify'), [
            'otp' => '000000',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertNull(EmailOtp::where('user_id', $user->id)->first()); // Invalidated
    }

    public function test_expired_otp_is_rejected_and_cleaned_up()
    {
        $user = User::create([
            'name' => 'Expired User',
            'email' => 'expired_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'user',
        ]);

        EmailOtp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make('999999'),
            'expires_at' => Carbon::now()->subMinute(), // Expired
            'attempts' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('verification.verify'), [
            'otp' => '999999',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertNull(EmailOtp::where('user_id', $user->id)->first());
    }

    public function test_resend_cooldown_is_enforced()
    {
        $user = User::create([
            'name' => 'Cooldown User',
            'email' => 'cooldown_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'user',
        ]);

        $otpService = new EmailOtpService();
        $otpService->generateAndSend($user);

        // Immediate resend should be blocked by cooldown
        $response = $this->actingAs($user)->post(route('verification.resend'));
        $response->assertSessionHas('error');
    }

    public function test_unverified_user_cannot_access_protected_dashboard_routes()
    {
        $vendor = User::create([
            'name' => 'Unverified Vendor',
            'email' => 'unverified_vendor_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'vendor',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($vendor)->get(route('vendor.dashboard'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_vendor_can_access_dashboard()
    {
        $vendor = User::create([
            'name' => 'Verified Vendor',
            'email' => 'verified_vendor_' . uniqid() . '@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'vendor',
            'email_verified_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($vendor)->get(route('vendor.dashboard'));
        $response->assertStatus(200);
    }
}
