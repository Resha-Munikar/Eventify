<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    protected EmailOtpService $otpService;

    public function __construct(EmailOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Resolve the target user from either Auth or session state.
     *
     * @param Request $request
     * @return User|null
     */
    protected function resolveUser(Request $request): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $userId = $request->session()->get('verify_user_id');
        if ($userId) {
            return User::find($userId);
        }

        return null;
    }

    /**
     * Show the OTP verification form.
     */
    public function showVerifyForm(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Please log in or register to verify your email.');
        }

        // If user is already verified, redirect to their role-appropriate destination
        if ($user->email_verified_at !== null) {
            return $this->redirectVerifiedUser($user, 'Your email is already verified.');
        }

        $cooldownRemaining = $this->otpService->getCooldownRemaining($user);
        $maskedEmail = $this->maskEmail($user->email);

        return view('auth.verify-otp', compact('user', 'maskedEmail', 'cooldownRemaining'));
    }

    /**
     * Verify the submitted 6-digit OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.required' => 'Please enter the 6-digit verification code.',
            'otp.size' => 'The verification code must be exactly 6 digits.',
            'otp.regex' => 'The verification code must contain numbers only.',
        ]);

        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Session expired. Please log in again.');
        }

        $result = $this->otpService->verify($user, $request->input('otp'));

        if (!$result['success']) {
            return back()->withErrors(['otp' => $result['message']])->withInput();
        }

        // Clean up pending session state
        $request->session()->forget('verify_user_id');

        // Log the user in if not already authenticated
        if (!Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
            $request->session()->regenerate();
        }

        return $this->redirectVerifiedUser($user, 'Your email has been verified successfully.');
    }

    /**
     * Resend a fresh 6-digit OTP code to the registered email address.
     */
    public function resendOtp(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Session expired. Please log in again.');
        }

        if ($user->email_verified_at !== null) {
            return $this->redirectVerifiedUser($user, 'Your email is already verified.');
        }

        if (!$this->otpService->canResend($user)) {
            $remaining = $this->otpService->getCooldownRemaining($user);
            return back()->with('error', "Please wait {$remaining} seconds before requesting another verification code.");
        }

        $result = $this->otpService->generateAndSend($user);

        if ($result['success']) {
            return back()->with('success', 'A new 6-digit verification code has been sent to your email.');
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Redirect verified user according to existing role architecture.
     *
     * @param User $user
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectVerifiedUser(User $user, string $message)
    {
        if ($user->role === 'admin') {
            return redirect()->route('chirps.adminIndex')->with('success', $message);
        }

        if ($user->role === 'vendor') {
            // Note: Future KYC check for vendors can be cleanly evaluated here
            return redirect()->route('vendor.dashboard')->with('success', $message);
        }

        return redirect()->route('welcome')->with('success', $message);
    }

    /**
     * Mask email address for privacy display.
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $name = $parts[0];
        $domain = $parts[1];

        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $maskedName = substr($name, 0, 1) . str_repeat('*', min(5, $len - 2)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}
