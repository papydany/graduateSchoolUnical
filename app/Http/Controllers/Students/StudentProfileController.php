<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Services\Students\StudentProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function __construct(private readonly StudentProfileService $profileService)
    {
    }

    public function index(): View
    {
        return view('pages.students.profile');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'registration_number' => ['required', 'string', 'max:255'],
        ]);

        $student = $this->profileService->findByRegistrationNumber($validated['registration_number']);

        if (! $student) {
            return back()
                ->withInput()
                ->withErrors(['registration_number' => 'No student record was found for this registration number.']);
        }

        if (! $student->hasIncompleteProfile()) {
            return back()
                ->withInput()
                ->with('student', $student->only(['surname', 'firstname', 'othername', 'matriculation_number']));
        }

        $request->session()->put('profile_student_id', $student->id);

        return redirect()->route('student.profile.complete');
    }

    public function complete(Request $request): View|RedirectResponse
    {
        $student = $this->profileService->pendingStudent($request->session()->get('profile_student_id'));

        if (! $student) {
            return redirect()->route('student.profile');
        }

        return view('pages.students.complete-profile', $this->profileService->getCompleteData($student));
    }

    public function update(Request $request): RedirectResponse
    {
        $student = $this->profileService->pendingStudent($request->session()->get('profile_student_id'));

        if (! $student) {
            return redirect()->route('student.profile');
        }

        $validated = $request->validate([
            'programme_of_study_id' => ['required', 'integer', Rule::exists('programme_of_studies', 'id')
                ->where('programme_id', $student->programme_id)
                ->where('faculty_id', $student->faculty_id)
                ->where('department_id', $student->department_id)],
            'specialization_id' => ['nullable', 'integer', Rule::exists('specializations', 'id')
                ->where('department_id', $student->department_id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->id)],
            'phone' => ['required', 'string', 'max:20'],
            'matrital_status' => ['required', Rule::in(StudentProfileService::MARITAL_STATUSES)],
            'gender' => ['required', Rule::in(StudentProfileService::GENDERS)],
            'state' => ['required', 'integer', Rule::exists('test.states', 'id')],
            'lga' => ['required', 'integer', Rule::exists('test.lgas', 'id')->where('state_id', $request->integer('state'))],
            'passport' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:200'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'matrital_status.required' => 'Please select your marital status.',
            'lga.exists' => 'The selected local government does not belong to the selected state.',
            'passport.max' => 'The passport photograph must not be larger than 200KB.',
        ]);

        $this->profileService->completeProfile($student, $validated, $request->file('passport'));

        $request->session()->forget('profile_student_id');

        return redirect()->route('student.login')
            ->with('status', 'Your profile has been completed. Log in with your matriculation number and password.');
    }

    public function lgas(int $stateId): JsonResponse
    {
        return response()->json($this->profileService->lgasForState($stateId));
    }
}
