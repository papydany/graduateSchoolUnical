<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Services\Students\StudentAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function __construct(private readonly StudentAuthService $authService)
    {
    }

    public function index(): View
    {
        return view('pages.students.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_type' => ['required', 'in:new,returning'],
            'matriculation_number' => ['required', 'string', 'max:255'],
            'pin' => ['required', 'string'],
        ], [
            'student_type.required' => 'Please select whether you are a new or returning student.',
        ]);

        $this->authService->login(
            $request,
            $validated['matriculation_number'],
            $validated['pin'],
            $validated['student_type'],
        );

        return redirect()->intended(route('student.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect()->route('student.login');
    }
}
