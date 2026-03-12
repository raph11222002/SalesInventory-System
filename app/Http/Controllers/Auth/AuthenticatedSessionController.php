<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate reCAPTCHA before authenticating
        $request->validate([
            'g-recaptcha-response' => ['required', 'captcha'],
        ], [
            'g-recaptcha-response.required' => 'Please complete the captcha.',
            'g-recaptcha-response.captcha'  => 'Captcha verification failed. Please try again.',
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        $guard = session('guard', 'web');

        // Redirect based on which guard was used
        if ($guard === 'staff') {
            return redirect()->intended(route('show.dashboard')); // or a staff-specific route
        }

        return redirect()->intended(route('show.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $guard = session('guard', 'web'); // default to web if not set

        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
