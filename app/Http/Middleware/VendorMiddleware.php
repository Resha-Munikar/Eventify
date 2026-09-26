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
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login.form');
        }

        if (Auth::user()->role === 'vendor') {
            return $next($request);
        }

        if (Auth::user()->role === 'admin') {
            if (str_contains($request->path(), 'kyc')) {
                return redirect()->route('admin.kyc.index')
                    ->with('warning', 'You are currently logged in as Administrator. To view the vendor resubmission form, please log in with the vendor account.');
            }
            return redirect()->route('chirps.adminIndex')->with('error', 'Unauthorized access.');
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
