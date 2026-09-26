<?php

namespace App\Http\Controllers\Course;

use App\Concerns\Trait\All;
use App\Exports\CourseTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\CourseImport;
use App\Models\Courses;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CourseController extends Controller
{
    use All;

    public function index(Request $request)
    {
        $courses = Courses::query()
            ->when($request->filled('search'), fn ($q) => $q
                ->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                        ->orWhere('code', 'like', "%{$request->search}%");
                }))
            ->when($request->filled('faculty_id'), fn ($q) => $q
                ->where('faculty_id', $request->faculty_id))
            ->when($request->filled('programme_id'), fn ($q) => $q
                ->where('programme_id', $request->programme_id))
            ->when($request->filled('semester'), fn ($q) => $q
                ->where('semester', $request->semester))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $faculties = DB::connection('test')->table('faculties')->orderBy('faculty_name')->pluck('faculty_name', 'id');
        $departments = DB::connection('test')->table('departments')->orderBy('department_name')->pluck('department_name', 'id');
        $programmes = DB::table('programmes')->pluck('name', 'id');

        return view('pages.courses.index', compact('courses', 'faculties', 'departments', 'programmes'));
    }

    public function create()
    {
        $faculties = $this->allFaculty();
        $programmes = $this->allProgramme();

        $oldRows = old('courses', [['code' => '', 'title' => '', 'unit' => '', 'semester' => '']]);

        return view('pages.courses.create', compact('faculties', 'programmes', 'oldRows'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'programme_id' => ['required', 'integer'],
            'courses' => ['required', 'array', 'min:1'],
            'courses.*.code' => ['required', 'string', 'max:20', 'distinct', Rule::unique('courses', 'code')],
            'courses.*.title' => ['required', 'string', 'max:255'],
            'courses.*.unit' => ['required', 'integer', 'min:1', 'max:6'],
            'courses.*.semester' => ['required', 'string', Rule::in(['1st Semester', '2nd Semester'])],
        ], [
            'courses.*.code.required' => 'Course code is required for every row.',
            'courses.*.code.unique' => 'One or more course codes already exist.',
            'courses.*.code.distinct' => 'Duplicate course codes found in the same submission.',
            'courses.*.title.required' => 'Course title is required for every row.',
            'courses.*.unit.required' => 'Credit unit is required for every row.',
            'courses.*.semester.required' => 'Semester is required for every row.',
        ]);

        $facultyId = (int) $request->faculty_id;
        $deptId = (int) $request->department_id;
        $programmeId = (int) $request->programme_id;
        $now = now();

        try {
            DB::transaction(function () use ($request, $facultyId, $deptId, $programmeId, $now) {
                $rows = [];
                foreach ($request->courses as $course) {
                    $rows[] = [
                        'uuid' => Str::uuid()->toString(),
                        'faculty_id' => $facultyId,
                        'department_id' => $deptId,
                        'programme_id' => $programmeId,
                        'code' => strtoupper(trim($course['code'])),
                        'title' => trim($course['title']),
                        'unit' => (int) $course['unit'],
                        'semester' => $course['semester'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                DB::table('courses')->insert($rows);
            });
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not save courses. Please try again.');
        }

        $count = count($request->courses);

        return redirect()
            ->route('courses.index')
            ->with('success', $count === 1 ? 'Course created successfully.' : "{$count} courses created successfully.");
    }

    public function importForm()
    {
        $faculties = Faculty::orderBy('faculty_name')->get();
        $programmes = Programme::orderBy('name')->get();

        return view('pages.courses.import', compact('faculties', 'programmes'));
    }


    public function downloadTemplate()
    {
        return Excel::download(new CourseTemplateExport, 'course_import_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'faculty_id' => ['required', 'integer', Rule::exists('test.faculties', 'id')],
            'department_id' => ['required', 'integer', Rule::exists('test.departments', 'id')],
            'programme_id' => ['required', 'integer', Rule::exists('programmes', 'id')],
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ]);

        $facultyId = $request->integer('faculty_id');

        $department = Department::where('faculty_id', $facultyId)
            ->find($request->integer('department_id'));

        if (! $department) {
            return back()->withInput()
                ->with('error', 'Please select a department that belongs to the selected faculty.');
        }

        $import = new CourseImport(
            facultyId: $facultyId,
            departmentId: $department->id,
            programmeId: $request->integer('programme_id'),
        );

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not read the uploaded file. Please make sure it is a valid Excel file.');
        }

        $errors = $import->failures()
            ->map(fn ($failure) => "Row {$failure->row()}: ".implode(' ', $failure->errors()))
            ->all();

        if ($import->errors()->isNotEmpty()) {
            $errors[] = $import->errors()->count().' row(s) failed to save due to a database error.';
        }

        if ($import->imported === 0 && $import->skipped === 0 && count($errors) > 0) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'No courses were imported.')
                ->with('import_errors', $errors);
        }

        $message = "{$import->imported} course(s) imported.";

        if ($import->skipped > 0) {
            $message .= " {$import->skipped} duplicate(s) skipped.";
        }

        return redirect()
            ->route('courses.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    public function show(Courses $course)
    {
        $faculty = $this->getFacultyByID($course->faculty_id);
        $department = $this->getDepartmentById($course->department_id);
        $programme = $this->getProgrammeById($course->programme_id);

        return view('pages.courses.show', compact('course', 'faculty', 'department', 'programme'));
    }

    public function edit(Courses $course)
    {
        $faculties = $this->allFaculty();
        $programmes = $this->allProgramme();

        return view('pages.courses.edit', compact('course', 'faculties', 'programmes'));
    }

    public function update(Request $request, Courses $course)
    {
        $request->validate([
            'faculty_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'programme_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'integer', Rule::in([1,2])],
        ]);

        try {
            $course->update([
                'faculty_id' => (int) $request->faculty_id,
                'department_id' => (int) $request->department_id,
                'programme_id' => (int) $request->programme_id,
                'title' => trim($request->title),
                'unit' => (int) $request->unit,
                'semester' => (int) $request->semester,
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not update course. Please try again.');
        }

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

  /*  public function destroy(Courses $course)
    {
        try {
            $course->delete();
        } catch (\Throwable $e) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Could not delete course. Please try again.');
        }

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }*/
}
