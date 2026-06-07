<?php

namespace App\Http\Controllers\Course;

use App\Concerns\Trait\All;
use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\RegisteredCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisteredCourseController extends Controller
{
    use All;

    public function index(Request $request)
    {
        $registeredCourses = RegisteredCourse::query()
            ->when($request->filled('search'), fn ($q) => $q
                ->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                      ->orWhere('code', 'like', "%{$request->search}%");
                }))
            ->when($request->filled('programme_of_study_id'), fn ($q) => $q
                ->where('programme_of_study_id', $request->programme_of_study_id))
            ->when($request->filled('semester'), fn ($q) => $q
                ->where('semester', $request->semester))
            ->when($request->filled('status'), fn ($q) => $q
                ->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $programmeOfStudies = DB::table('programme_of_studies')
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('pages.registered-courses.index', compact('registeredCourses', 'programmeOfStudies'));
    }

    public function create()
    {
        $courses = Courses::orderBy('code')->get(['id', 'code', 'title', 'unit', 'semester']);

        $programmeOfStudies = DB::table('programme_of_studies')
            ->orderBy('name')
            ->get(['id', 'name']);

        $levels   = $this->levels();
        $sessions = $this->sessionOptions();

        return view('pages.registered-courses.create', compact('courses', 'programmeOfStudies', 'levels', 'sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'             => ['required', 'integer', 'exists:courses,id'],
            'programme_of_study_id' => ['required', 'integer', 'exists:programme_of_studies,id'],
            'level_id'              => ['required', 'integer', Rule::in(array_keys($this->levels()))],
            'session'               => ['required', 'integer', 'min:2000', 'max:2099'],
            'status'                => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $course = Courses::findOrFail($request->course_id);

        $duplicate = RegisteredCourse::where('course_id', $course->id)
            ->where('programme_of_study_id', $request->programme_of_study_id)
            ->where('level_id', $request->level_id)
            ->where('session', $request->session)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->with('error', 'This course is already registered for the selected programme, level, and session.');
        }

        try {
            RegisteredCourse::create([
                'course_id'             => $course->id,
                'programme_of_study_id' => (int) $request->programme_of_study_id,
                'level_id'              => (int) $request->level_id,
                'code'                  => $course->code,
                'title'                 => $course->title,
                'unit'                  => $course->unit,
                'semester'              => $course->semester === '1st Semester' ? 1 : 2,
                'session'               => (int) $request->session,
                'status'                => $request->status,
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not register course. Please try again.');
        }

        return redirect()
            ->route('registered-courses.index')
            ->with('success', 'Course registered successfully.');
    }

    public function show(RegisteredCourse $registeredCourse)
    {
        $programmeOfStudy = DB::table('programme_of_studies')
            ->find($registeredCourse->programme_of_study_id);

        $levels = $this->levels();

        return view('pages.registered-courses.show', compact('registeredCourse', 'programmeOfStudy', 'levels'));
    }

    public function edit(RegisteredCourse $registeredCourse)
    {
        $courses = Courses::orderBy('code')->get(['id', 'code', 'title', 'unit', 'semester']);

        $programmeOfStudies = DB::table('programme_of_studies')
            ->orderBy('name')
            ->get(['id', 'name']);

        $levels   = $this->levels();
        $sessions = $this->sessionOptions();

        return view('pages.registered-courses.edit', compact(
            'registeredCourse', 'courses', 'programmeOfStudies', 'levels', 'sessions'
        ));
    }

    public function update(Request $request, RegisteredCourse $registeredCourse)
    {
        $request->validate([
            'course_id'             => ['required', 'integer', 'exists:courses,id'],
            'programme_of_study_id' => ['required', 'integer', 'exists:programme_of_studies,id'],
            'level_id'              => ['required', 'integer', Rule::in(array_keys($this->levels()))],
            'session'               => ['required', 'integer', 'min:2000', 'max:2099'],
            'status'                => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $course = Courses::findOrFail($request->course_id);

        $duplicate = RegisteredCourse::where('course_id', $course->id)
            ->where('programme_of_study_id', $request->programme_of_study_id)
            ->where('level_id', $request->level_id)
            ->where('session', $request->session)
            ->where('id', '!=', $registeredCourse->id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->with('error', 'This course is already registered for the selected programme, level, and session.');
        }

        try {
            $registeredCourse->update([
                'course_id'             => $course->id,
                'programme_of_study_id' => (int) $request->programme_of_study_id,
                'level_id'              => (int) $request->level_id,
                'code'                  => $course->code,
                'title'                 => $course->title,
                'unit'                  => $course->unit,
                'semester'              => $course->semester === '1st Semester' ? 1 : 2,
                'session'               => (int) $request->session,
                'status'                => $request->status,
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not update registered course. Please try again.');
        }

        return redirect()
            ->route('registered-courses.index')
            ->with('success', 'Registered course updated successfully.');
    }

    public function destroy(RegisteredCourse $registeredCourse)
    {
        try {
            $registeredCourse->delete();
        } catch (\Throwable $e) {
            return redirect()
                ->route('registered-courses.index')
                ->with('error', 'Could not remove registered course. Please try again.');
        }

        return redirect()
            ->route('registered-courses.index')
            ->with('success', 'Registered course removed successfully.');
    }

    private function levels(): array
    {
        return [1 => 'Year 1', 2 => 'Year 2', 3 => 'Year 3'];
    }

    private function sessionOptions(): array
    {
        $year     = (int) date('Y');
        $sessions = [];
        for ($y = $year + 1; $y >= $year - 4; $y--) {
            $sessions[$y] = "{$y}/" . ($y + 1);
        }
        return $sessions;
    }
}
