<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function index(): View
    {
        return view('pages.students.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'student_type' => ['required', 'in:new,returning'],
            'matriculation_number' => ['required', 'string', 'max:255'],
            'pin' => ['required', 'string'],
        ], [
            'student_type.required' => 'Please select whether you are a new or returning student.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('matriculation_number')).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'matriculation_number' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
            ]);
        }

        $credentials = [
            'matriculation_number' => trim($request->input('matriculation_number')),
            'password' => $request->input('pin'),
        ];

        if (! Auth::guard('student')->attempt($credentials)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'matriculation_number' => 'The matriculation number or PIN is incorrect.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();
        $request->session()->put('student_type', $request->input('student_type'));

        return redirect()->intended(route('student.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
