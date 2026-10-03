<?php

namespace App\Http\Controllers\Admission;

use App\Exports\StudentTemplateExport;
use App\Concerns\Trait\All;
use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Programme;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use All;

    public function index(Request $request)
    {
        $students = Student::query()
            ->when($request->filled('search'), fn ($q) => $q
                ->where(function ($q) use ($request) {
                    $q->where('surname', 'like', "%{$request->search}%")
                        ->orWhere('firstname', 'like', "%{$request->search}%")
                        ->orWhere('othername', 'like', "%{$request->search}%")
                        ->orWhere('registration_number', 'like', "%{$request->search}%");
                }))
            ->when($request->filled('faculty_id'), fn ($q) => $q
                ->where('faculty_id', $request->faculty_id))
            ->when($request->filled('programme_id'), fn ($q) => $q
                ->where('programme_id', $request->programme_id))
            ->when($request->filled('entry_session'), fn ($q) => $q
                ->where('entry_session', $request->entry_session))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $faculties = DB::connection('test')->table('faculties')->orderBy('faculty_name')->pluck('faculty_name', 'id');
        $departments = DB::connection('test')->table('departments')->orderBy('department_name')->pluck('department_name', 'id');
        $programmes = DB::table('programmes')->pluck('name', 'id');
        $sessions = $this->sessionOptions();

        return view('pages.admission.students.index', compact('students', 'faculties', 'departments', 'programmes', 'sessions'));
    }

    public function importForm()
    {
        $faculties = Faculty::orderBy('faculty_name')->get();
        $programmes = Programme::orderBy('name')->get();
        $sessions = $this->sessionOptions();

        return view('pages.admission.students.import', compact('faculties', 'programmes', 'sessions'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new StudentTemplateExport, 'students_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'faculty_id' => ['required', 'integer', Rule::exists('test.faculties', 'id')],
            'department_id' => ['required', 'integer', Rule::exists('test.departments', 'id')],
            'programme_id' => ['required', 'integer', Rule::exists('programmes', 'id')],
            'entry_session' => ['required', 'integer', Rule::in(array_keys($this->sessionOptions()))],
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ], [
            'file.required' => 'Please choose an Excel file to upload.',
            'file.uploaded' => 'The file failed to upload. Make sure it is not larger than '.ini_get('upload_max_filesize').'B and try again.',
            'file.mimes' => 'The file must be an Excel file (.xlsx or .xls).',
        ]);

        $facultyId = $request->integer('faculty_id');

        $department = Department::where('faculty_id', $facultyId)
            ->find($request->integer('department_id'));

        if (! $department) {
            return back()->withInput()
                ->with('error', 'Please select a department that belongs to the selected faculty.');
        }

        $import = new StudentImport(
            facultyId: $facultyId,
            departmentId: $department->id,
            programmeId: $request->integer('programme_id'),
            entrySession: $request->integer('entry_session'),
            uploadedBy: $request->user()?->id,
        );

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            Log::error('Student import failed', ['file' => $request->file('file')->getClientOriginalName(), 'exception' => $e]);

            return back()->withInput()
                ->with('error', 'Could not read the uploaded file. Please make sure it is a valid Excel file based on the template.')
                ->with('import_errors', [Str::limit($e->getMessage(), 300)]);
        }

        $errors = $import->failures()
            ->map(fn ($failure) => "Row {$failure->row()}: ".implode(' ', $failure->errors()))
            ->all();

        $errors = array_merge($errors, $import->saveErrors);

        if ($import->imported === 0 && $import->skipped === 0 && count($errors) > 0) {
            return redirect()
                ->route('admission.students.index')
                ->with('error', 'No students were imported.')
                ->with('import_errors', $errors);
        }

        $message = "{$import->imported} student(s) imported.";

        if ($import->skipped > 0) {
            $message .= " {$import->skipped} duplicate registration number(s) skipped.";
        }

        return redirect()
            ->route('admission.students.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }
}
