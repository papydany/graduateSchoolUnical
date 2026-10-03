<?php

namespace App\Services\Students;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentAuthService
{
    private const MAX_ATTEMPTS = 5;

    /**
     * @throws ValidationException when the credentials are wrong or attempts are throttled
     */
    public function login(Request $request, string $matriculationNumber, string $pin, string $studentType): void
    {
        $throttleKey = Str::transliterate(Str::lower($matriculationNumber).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'matriculation_number' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
            ]);
        }

        $credentials = [
            'matriculation_number' => trim($matriculationNumber),
            'password' => $pin,
        ];

        if (! Auth::guard('student')->attempt($credentials)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'matriculation_number' => 'The matriculation number or PIN is incorrect.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();
        $request->session()->put('student_type', $studentType);
    }

    public function logout(Request $request): void
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
