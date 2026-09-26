<?php

namespace App\Http\Controllers\Setup;

use App\Concerns\Trait\All;
use App\Exports\SpecializationTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\SpecializationImport;
use App\Models\Department;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SpecializationController extends Controller
{
    use All;

    public function index(Request $request)
    {
        $specializations = Specialization::query()
            ->when($request->filled('search'), fn ($q) => $q
                ->where('name', 'like', "%{$request->search}%"))
            ->when($request->filled('faculty_id'), fn ($q) => $q
                ->where('faculty_id', $request->faculty_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $faculties = DB::connection('test')->table('faculties')->pluck('faculty_name', 'id');
        $departments = DB::connection('test')->table('departments')->pluck('department_name', 'id');

        return view('pages.setup.specialization.index', compact(
            'specializations', 'faculties', 'departments'
        ));
    }

    public function create()
    {
        $faculty = $this->allFaculty();

        return view('pages.setup.specialization.create', compact('faculty'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'faculty' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
        ]);

        $name = strtoupper($request->name);
        $facultyId = (int) $request->faculty;
        $deptId = (int) $request->department_id;

        $duplicate = Specialization::where('faculty_id', $facultyId)
            ->where('department_id', $deptId)
            ->where('name', $name)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->with('error', 'This specialization already exists for the selected department.');
        }

        try {
            DB::table('specializations')->insert([
                'uuid' => Str::uuid()->toString(),
                'name' => $name,
                'faculty_id' => $facultyId,
                'department_id' => $deptId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not save specialization. Please try again.');
        }

        return redirect()
            ->route('setup.specialization.index')
            ->with('success', 'Specialization created successfully.');
    }

    public function importForm()
    {
        $faculties = $this->allFaculty();

        return view('pages.setup.specialization.import', compact('faculties'));
    }

    /**
     * AJAX: return departments for a given faculty as JSON.
     */
    public function getDepartments(int $facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)
            ->orderBy('department_name')
            ->get(['id', 'department_name']);

        return response()->json($departments);
    }

    public function downloadTemplate()
    {
        return Excel::download(new SpecializationTemplateExport, 'specialization_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'faculty_id' => ['required', 'integer', Rule::exists('test.faculties', 'id')],
            'department_id' => ['required', 'integer', Rule::exists('test.departments', 'id')],
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ]);

        $facultyId = $request->integer('faculty_id');

        $department = Department::where('faculty_id', $facultyId)
            ->find($request->integer('department_id'));

        if (! $department) {
            return back()->withInput()
                ->with('error', 'Please select a department that belongs to the selected faculty.');
        }

        $import = new SpecializationImport(
            facultyId: $facultyId,
            departmentId: $department->id,
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

        if ($import->imported === 0 && $import->skipped === 0 && count($errors) > 0) {
            return redirect()
                ->route('setup.specialization.index')
                ->with('error', 'No specializations were imported.')
                ->with('import_errors', $errors);
        }

        $message = "{$import->imported} specialization(s) imported.";

        if ($import->skipped > 0) {
            $message .= " {$import->skipped} duplicate(s) skipped.";
        }

        return redirect()
            ->route('setup.specialization.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    public function show(Specialization $specialization)
    {
        $faculty = $this->getFacultyByID($specialization->faculty_id);
        $department = $this->getDepartmentById($specialization->department_id);

        return view('pages.setup.specialization.show', compact(
            'specialization', 'faculty', 'department'
        ));
    }

    public function edit(Specialization $specialization)
    {
        $faculty = $this->allFaculty();

        return view('pages.setup.specialization.edit', compact('specialization', 'faculty'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'faculty' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
        ]);

        $name = strtoupper($request->name);
        $facultyId = (int) $request->faculty;
        $deptId = (int) $request->department_id;

        $duplicate = Specialization::where('faculty_id', $facultyId)
            ->where('department_id', $deptId)
            ->where('name', $name)
            ->where('id', '!=', $specialization->id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->with('error', 'Another specialization with these details already exists.');
        }

        try {
            $specialization->update([
                'name' => $name,
                'faculty_id' => $facultyId,
                'department_id' => $deptId,
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not update specialization. Please try again.');
        }

        return redirect()
            ->route('setup.specialization.index')
            ->with('success', 'Specialization updated successfully.');
    }

    public function destroy(Specialization $specialization)
    {
        try {
            $specialization->delete();
        } catch (\Throwable $e) {
            return redirect()
                ->route('setup.specialization.index')
                ->with('error', 'Could not delete specialization. Please try again.');
        }

        return redirect()
            ->route('setup.specialization.index')
            ->with('success', 'Specialization deleted successfully.');
    }
}
