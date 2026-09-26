<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected EmailOtpService $otpService;

    public function __construct(EmailOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,vendor',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        ActivityLogger::log('registered', 'Registered an account as ' . ucfirst($user->role), $user, $user);

        // Keep auth and session state for immediate OTP verification
        Auth::login($user);
        $request->session()->put('verify_user_id', $user->id);

        // Generate and dispatch 6-digit OTP to user email
        $otpResult = $this->otpService->generateAndSend($user);

        $flashMessage = $otpResult['success']
            ? 'Registration successful! A 6-digit verification code has been sent to ' . $user->email . '.'
            : 'Account created! ' . $otpResult['message'];

        return redirect()
            ->route('verification.notice')
            ->with('success', $flashMessage);
    }

    public function showLoginForm(Request $request)
    {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->query('redirect')]);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credential)) {
            $request->session()->regenerate();

            $user = Auth::user();

            ActivityLogger::log('logged_in', 'Logged in to the system', $user, $user);

            // Intercept unverified users and redirect to OTP verification
            if ($user->email_verified_at === null) {
                $request->session()->put('verify_user_id', $user->id);

                if ($this->otpService->canResend($user)) {
                    $this->otpService->generateAndSend($user);
                }

                return redirect()
                    ->route('verification.notice')
                    ->with('warning', 'Please verify your email address to complete sign in.');
            }

            // 1. If explicit redirect query is provided, redirect there
            if ($request->filled('redirect')) {
                return redirect($request->input('redirect'));
            }

            // 2. If vendor KYC is currently rejected, ALWAYS prioritize taking them straight to KYC Resubmission!
            if ($user->role === 'vendor') {
                $user->load('kyc');
                if ($user->kyc && $user->kyc->isRejected()) {
                    session()->forget('url.intended');
                    return redirect()->route('vendor.kyc.resubmit')
                        ->with('warning', 'Your KYC verification was rejected. Please review feedback and resubmit your documents below.');
                }
            }

            // 3. Handle intended destination
            $intended = session('url.intended');
            session()->forget('url.intended');

            if ($intended) {
                return redirect($intended);
            }

            if ($user->role === 'admin') {
                return redirect()->route('chirps.adminIndex');
            } elseif ($user->role === 'vendor') {
                return redirect()->route('vendor.dashboard');
            }

            return redirect()->route('welcome');
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLogger::log('logged_out', 'Logged out of the system', $user, $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
