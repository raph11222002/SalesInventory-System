<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    private int $maxAttempts = 2;
    private int $decaySeconds = 15;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        $remember = $this->boolean('remember');
        $authenticated = false;

        // Check if staff account is deactivated
        $staff = \App\Models\Staff::where('email', $this->email)->first();

        if ($staff && !$staff->is_active) {
            throw ValidationException::withMessages([
                'email' => 'This account has been deactivated. Please contact your administrator.',
            ]);
        }

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            session(['guard' => 'web']);
            $authenticated = true;
        } elseif (Auth::guard('staff')->attempt($credentials, $remember)) {
            session(['guard' => 'staff']);
            $authenticated = true;
        }

        if ($authenticated) {
            RateLimiter::clear($this->throttleKey());
            Cache::forget($this->throttleKey() . ':lockout_until');
            return;
        }

        RateLimiter::hit($this->throttleKey(), $this->decaySeconds);

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $lockoutKey = $this->throttleKey() . ':lockout_until';

        if (!Cache::has($lockoutKey)) {
            Cache::put($lockoutKey, now()->addSeconds($this->decaySeconds)->timestamp, $this->decaySeconds);
        }

        $seconds = max(0, Cache::get($lockoutKey) - now()->timestamp);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}