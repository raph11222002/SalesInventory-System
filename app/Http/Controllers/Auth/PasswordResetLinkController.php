<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if email belongs to a deactivated staff
        $staff = Staff::where('email', $request->email)->first();

        if ($staff && !$staff->is_active) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This account has been deactivated. Please contact your administrator.']);
        }

        // Try staff broker first, then fall back to default (web/admin)
        $isStaff = $staff !== null;

        $status = $isStaff
            ? Password::broker('staffs')->sendResetLink($request->only('email'))
            : Password::broker()->sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}