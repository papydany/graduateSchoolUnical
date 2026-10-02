<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function index(): View
    {
        return view('pages.students.profile');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'registration_number' => ['required', 'string', 'max:255'],
        ]);

        $student = Student::where('registration_number', trim($validated['registration_number']))->first();

        if (! $student) {
            return back()
                ->withInput()
                ->withErrors(['registration_number' => 'No student record was found for this registration number.']);
        }

        return back()
            ->withInput()
            ->with('student', $student->only(['surname', 'firstname', 'othername', 'matriculation_number']));
    }
}
