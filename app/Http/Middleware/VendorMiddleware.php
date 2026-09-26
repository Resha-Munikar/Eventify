<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If not logged in at all, remember intended target and redirect to login
        if (!Auth::check()) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login.form')
                ->with('warning', 'Please sign in with your Vendor account to continue.');
        }

        $user = Auth::user();

        // 2. If logged in as vendor, allow request to proceed
        if ($user->role === 'vendor') {
            return $next($request);
        }

        // 3. If logged in as Admin or Customer and trying to access a vendor KYC / organizer page
        $currentRole = $user->role;
        $targetUrl = $request->fullUrl();

        // Log out the active non-vendor session so the vendor can log in cleanly
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session(['url.intended' => $targetUrl]);

        return redirect()->route('login.form')
            ->with('warning', 'You were signed in as ' . ucfirst($currentRole) . '. Please sign in with the Vendor account to access the KYC resubmission page.');
    }
}
