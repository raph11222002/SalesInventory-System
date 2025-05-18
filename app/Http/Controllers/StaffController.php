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
        $staffs = Staff::where('admin_id', Auth::guard('web')->id())->get();

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
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:' . Staff::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $staff = Staff::create([
            'admin_id' => Auth::id(),
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($staff));

        //Auth::login($staff);

        return redirect()->route('show.register.staff')->with('success', 'Registered successfully.');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
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
            'username' => 'Invalid username or password.',
        ])->onlyInput('username');
    }
    public function logout()
    {
        Auth::guard('staff')->logout();
        
        return redirect('/');
    }
}
