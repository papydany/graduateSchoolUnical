<?php

namespace App\Http\Controllers\Course;

use App\Concerns\Trait\All;
use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\ProgrammeOfStudy;
use App\Models\RegisteredCourse;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisteredCourseController extends Controller
{
    use All;

    /**
     * Filter keys accepted by the list/search endpoint that map 1:1 to an
     * integer equality column on registered_courses.
     */
    private const SIMPLE_FILTERS = [
        'session',
        'semester',
        'level_id',
        'programme_type_id',
        'specialization_id',
        'programme_of_study_id',
    ];

    public function index(Request $request)
    {
        $faculties = $this->allFaculty();
        $programmes = $this->allProgramme();
        $levels = $this->levels();

        $course = null;
        $registeredCourses = null;

        if ($request->filled('course_id')) {
            $course = Courses::find($request->integer('course_id'));
            $registeredCourses = RegisteredCourse::where('course_id', $request->integer('course_id'))
                ->latest()
                ->get();
        }

        return view('pages.registered-courses.index', compact(
            'faculties', 'programmes', 'levels', 'course', 'registeredCourses'
        ));
    }

    public function list(Request $request)
    {
        $levels = $this->levels();
        $sessions = $this->sessionOptions();
        $faculties = $this->allFaculty();
        $programmes = $this->allProgramme();
        $programmeTypes = $this->allProgrammeType();

        $narrowsProgrammeOfStudy = $request->filled('faculty_id')
            || $request->filled('department_id')
            || $request->filled('programme_id');

        $registeredCourses = RegisteredCourse::query()
            ->when($request->filled('search'), fn ($q) => $q
                ->where(function ($q) use ($request) {
                    $q->where('code', 'like', "%{$request->search}%")
                        ->orWhere('title', 'like', "%{$request->search}%");
                }));

        foreach (self::SIMPLE_FILTERS as $field) {
            $registeredCourses->when($request->filled($field), fn ($q) => $q
                ->where($field, $request->integer($field)));
        }

        $registeredCourses
            ->when(!$request->filled('programme_of_study_id') && $narrowsProgrammeOfStudy, function ($q) use ($request) {
                $programmeOfStudyIds = DB::table('programme_of_studies')
                    ->when($request->filled('faculty_id'), fn ($q) => $q->where('faculty_id', $request->integer('faculty_id')))
                    ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->integer('department_id')))
                    ->when($request->filled('programme_id'), fn ($q) => $q->where('programme_id', $request->integer('programme_id')))
                    ->pluck('id');

                $q->whereIn('programme_of_study_id', $programmeOfStudyIds);
            });

        $registeredCourses = $registeredCourses
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $selectedFaculty = $request->filled('faculty_id') ? $this->getFacultyByID($request->integer('faculty_id')) : null;
        $selectedDepartment = $request->filled('department_id') ? $this->getDepartmentById($request->integer('department_id')) : null;
        $selectedProgramme = $request->filled('programme_id') ? $this->getProgrammeById($request->integer('programme_id')) : null;
        $selectedProgrammeType = $request->filled('programme_type_id') ? $this->getProgrammeTypeById($request->integer('programme_type_id')) : null;
        $selectedProgrammeOfStudy = $request->filled('programme_of_study_id')
            ? DB::table('programme_of_studies')->find($request->integer('programme_of_study_id'))
            : null;
        $selectedSpecialization = $request->filled('specialization_id')
            ? Specialization::find($request->integer('specialization_id'))
            : null;

        return view('pages.registered-courses.list', compact(
            'registeredCourses', 'levels', 'sessions', 'faculties', 'programmes', 'programmeTypes',
            'selectedFaculty', 'selectedDepartment', 'selectedProgramme', 'selectedProgrammeType',
            'selectedProgrammeOfStudy', 'selectedSpecialization'
        ));
    }

    public function getCourses(Request $request)
    {
        $faculties = $this->allFaculty();
        $programmes = $this->allProgramme();
        $programmeTypes = $this->allProgrammeType();
        $levels = $this->levels();
        $sessions = $this->sessionOptions();

        $request->validate([
            'faculty_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'programme_id' => ['required', 'integer'],
            'semester' => ['required', 'integer'],
        ]);

        $facultyId = $request->integer('faculty_id');
        $departmentId = $request->integer('department_id');
        $programmeId = $request->integer('programme_id');
        $semesterInt = $request->integer('semester');
        $semester = $this->semesterLabel($semesterInt);

        $selectedFaculty = $this->getFacultyByID($facultyId);
        $selectedDepartment = $this->getDepartmentById($departmentId);
        $selectedProgramme = $this->getProgrammeById($programmeId);

        $query = Courses::where('faculty_id', $facultyId)
            ->where('department_id', $departmentId)
            ->where('programme_id', $programmeId)
            ->where('semester', $semester)
            ->orderBy('code')
            ->get(['id', 'code', 'title', 'unit', 'semester']);

        return view('pages.registered-courses.index', compact(
            'query', 'faculties', 'programmes', 'programmeTypes', 'levels', 'sessions',
            'facultyId', 'departmentId', 'programmeId', 'semesterInt',
            'selectedFaculty', 'selectedDepartment', 'selectedProgramme'
        ));
    }

    /**
     * AJAX: programmes of study for the destination picker, scoped to the
     * selected department and programme (both are required).
     */
    public function getProgrammeOfStudies(Request $request)
    {
        $request->validate([
            'department_id' => ['required', 'integer'],
            'programme_id' => ['required', 'integer'],
        ]);

        $programmeOfStudies = ProgrammeOfStudy::where('department_id', $request->integer('department_id'))
            ->where('programme_id', $request->integer('programme_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($programmeOfStudies);
    }

    /**
     * AJAX: specializations for the destination picker, scoped to the
     * selected department.
     */
    public function getSpecializations(Request $request)
    {
        $request->validate([
            'department_id' => ['required', 'integer'],
        ]);

        $specializations = Specialization::where('department_id', $request->integer('department_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($specializations);
    }

    /**
     * AJAX: departments/programmes/semesters for the catalog filter, scoped to
     * whichever faculty (and optionally department) the user has selected.
     */
    public function filterOptions(Request $request)
    {
        $request->validate([
            'faculty_id' => ['required', 'integer'],
            'department_id' => ['nullable', 'integer'],
            'programme_id' => ['nullable', 'integer'],
        ]);

        return response()->json([
            'departments' => $this->departmentsForFaculty($request->integer('faculty_id')),
            'programmes' => $this->programmesForFilters($request->integer('faculty_id'), $request->integer('department_id') ?: null),
            'semesters' => $this->semestersForFilters(
                $request->integer('faculty_id'),
                $request->integer('department_id') ?: null,
                $request->integer('programme_id') ?: null
            ),
        ]);
    }

    private function departmentsForFaculty(int $facultyId)
    {
        return DB::connection('test')->table('departments')
            ->where('faculty_id', $facultyId)
            ->orderBy('department_name')
            ->get(['id', 'department_name']);
    }

    private function programmesForFilters(int $facultyId, ?int $departmentId)
    {
        $programmeIds = Courses::query()
            ->where('faculty_id', $facultyId)
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->distinct()
            ->pluck('programme_id');

        return DB::table('programmes')
            ->whereIn('id', $programmeIds)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function semestersForFilters(int $facultyId, ?int $departmentId, ?int $programmeId)
    {
        return Courses::query()
            ->where('faculty_id', $facultyId)
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->when($programmeId, fn ($q) => $q->where('programme_id', $programmeId))
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');
    }

    public function storeMany(Request $request)
    {
        $request->validate([
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
            'programme_of_study_id' => ['required', 'integer', 'exists:programme_of_studies,id'],
            'level_id' => ['required', 'integer', Rule::in(array_keys($this->levels()))],
            'session' => ['required', 'integer', 'min:2000', 'max:2099'],
            'dest_specialization_id' => ['required', 'integer'],
            'dest_programme_type_id' => ['required', 'integer'],
        ]);

        $courses = Courses::whereIn('id', $request->course_ids)->get();

        $programmeOfStudyId = $request->integer('programme_of_study_id');
        $specializationId = $request->integer('dest_specialization_id');
        $programmeTypeId = $request->integer('dest_programme_type_id');
        $level = $request->integer('level_id');
        $session = $request->integer('session');

        $created = 0;
        $skipped = 0;

        try {
            foreach ($courses as $course) {
                if ($this->isDuplicateRegistration($course->id, $programmeOfStudyId, $specializationId, $programmeTypeId, $level, $session)) {
                    $skipped++;

                    continue;
                }

                RegisteredCourse::create([
                    'course_id' => $course->id,
                    'programme_of_study_id' => $programmeOfStudyId,
                    'specialization_id' => $specializationId,
                    'programme_type_id' => $programmeTypeId,
                    'level_id' => $level,
                    'code' => $course->code,
                    'title' => $course->title,
                    'unit' => $course->unit,
                    'semester' => $this->semesterToInt($course->semester),
                    'session' => $session,
                ]);
                $created++;
            }
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not register the selected courses. Please try again.');
        }

        $message = $created === 1 ? '1 course registered successfully.' : "{$created} courses registered successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped (already registered).";
        }

        return redirect()
            ->route('registered-courses.index')
            ->with($created > 0 ? 'success' : 'error', $created > 0 ? $message : 'No courses were registered; all selected courses were already registered.');
    }

    public function create()
    {
        return view('pages.registered-courses.create', $this->registrationFormOptions());
    }

    public function store(Request $request)
    {
        $request->validate($this->registrationValidationRules());

        $course = Courses::findOrFail($request->course_id);

        if ($this->isDuplicateRegistration(
            $course->id,
            $request->integer('programme_of_study_id'),
            $request->integer('specialization_id'),
            $request->integer('programme_type_id'),
            $request->integer('level_id'),
            $request->integer('session')
        )) {
            return back()->withInput()
                ->with('error', 'This course is already registered for the selected programme, level, and session.');
        }

        try {
            RegisteredCourse::create([
                'course_id' => $course->id,
                'programme_of_study_id' => $request->integer('programme_of_study_id'),
                'specialization_id' => $request->integer('specialization_id'),
                'programme_type_id' => $request->integer('programme_type_id'),
                'level_id' => $request->integer('level_id'),
                'code' => $course->code,
                'title' => $course->title,
                'unit' => $course->unit,
                'semester' => $this->semesterToInt($course->semester),
                'session' => $request->integer('session'),
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

        $faculty = $programmeOfStudy ? $this->getFacultyByID($programmeOfStudy->faculty_id) : null;
        $department = $programmeOfStudy ? $this->getDepartmentById($programmeOfStudy->department_id) : null;
        $programme = $programmeOfStudy ? $this->getProgrammeById($programmeOfStudy->programme_id) : null;

        $programmeType = $registeredCourse->programme_type_id
            ? $this->getProgrammeTypeById($registeredCourse->programme_type_id)
            : null;

        $specialization = $registeredCourse->specialization_id
            ? Specialization::find($registeredCourse->specialization_id)
            : null;

        $levels = $this->levels();

        return view('pages.registered-courses.show', compact(
            'registeredCourse', 'programmeOfStudy', 'faculty', 'department', 'programme',
            'programmeType', 'specialization', 'levels'
        ));
    }

    public function edit(RegisteredCourse $registeredCourse)
    {
        return view('pages.registered-courses.edit', [
            'registeredCourse' => $registeredCourse,
            'programmeOfStudies' => DB::table('programme_of_studies')->orderBy('name')->get(['id', 'name']),
            'specializations' => Specialization::orderBy('name')->get(['id', 'name']),
            'programmeTypes' => $this->allProgrammeType(),
            'levels' => $this->levels(),
        ]);
    }

    /**
     * Only the title and credit unit may be corrected after registration —
     * the course, programme, level, session, specialization, and programme
     * type are fixed at registration time and are not editable here.
     */
    public function update(Request $request, RegisteredCourse $registeredCourse)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        try {
            $registeredCourse->update([
                'title' => $request->input('title'),
                'unit' => $request->integer('unit'),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not update registered course. Please try again.');
        }

        return redirect()
            ->route('registered-courses.list')
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

    /**
     * Shared lookup data for the create/edit registration forms.
     */
    private function registrationFormOptions(): array
    {
        return [
            'courses' => Courses::orderBy('code')->get(['id', 'code', 'title', 'unit', 'semester']),
            'programmeOfStudies' => DB::table('programme_of_studies')->orderBy('name')->get(['id', 'name']),
            'specializations' => Specialization::orderBy('name')->get(['id', 'name']),
            'programmeTypes' => $this->allProgrammeType(),
            'levels' => $this->levels(),
            'sessions' => $this->sessionOptions(),
        ];
    }

    /**
     * Shared validation rules for storing/updating a single registered course.
     */
    private function registrationValidationRules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'programme_of_study_id' => ['required', 'integer', 'exists:programme_of_studies,id'],
            'specialization_id' => ['required', 'integer', 'exists:specializations,id'],
            'programme_type_id' => ['required', 'integer', 'exists:programme_types,id'],
            'level_id' => ['required', 'integer', Rule::in(array_keys($this->levels()))],
            'session' => ['required', 'integer', 'min:2000', 'max:2099'],
        ];
    }

    /**
     * Whether a registration with this exact combination already exists,
     * excluding the given registered course id (used when updating).
     */
    private function isDuplicateRegistration(
        int $courseId,
        int $programmeOfStudyId,
        int $specializationId,
        int $programmeTypeId,
        int $levelId,
        int $session,
        ?int $excludeId = null
    ): bool {
        return RegisteredCourse::where('course_id', $courseId)
            ->where('programme_of_study_id', $programmeOfStudyId)
            ->where('specialization_id', $specializationId)
            ->where('programme_type_id', $programmeTypeId)
            ->where('level_id', $levelId)
            ->where('session', $session)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    private function semesterToInt(string $semester): int
    {
        return $semester === '1st Semester' ? 1 : 2;
    }

    private function semesterLabel(int $semester): string
    {
        return $semester === 1 ? '1st Semester' : '2nd Semester';
    }
}
