<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display the registration view.
     */
    public function showStaffRegister(): View
    {
        $staffs = Staff::where('is_active', 1)->get();

        return view('auth.register_staff', compact('staffs'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Staff::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($staff));

        //Auth::login($staff);

        return redirect()->route('show.register.staff')->with('success', 'Registered successfully.');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();

            logger('Login success:', [Auth::guard('staff')->user()]);

            return redirect()->route('record_sales');
        } else {
            logger('Login failed for', $credentials);
        }

        return back()->withErrors([
            'email' => 'Invalid username or password.',
        ])->onlyInput('email');
    }
    public function logout()
    {
        Auth::guard('staff')->logout();

        return redirect('/');
    }

    public function deactivate($id, Request $request)
    {
        $user = Staff::findOrFail($id);
        $user->is_active = $request->input('is_active', 0);
        $user->save();

        return redirect()->back()->with('status', 'Account deactivated successfully.');
    }

}
