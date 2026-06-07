<?php

namespace App\Http\Controllers\Setup;

use App\Concerns\Trait\All;
use App\Http\Controllers\Controller;
use App\Models\ProgrammeOfStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgrammeOfStudyController extends Controller
{
    use All;

    public function index(Request $request)
    {
        $programmes = ProgrammeOfStudy::with('durations')
            ->when($request->filled('search'), fn ($q) => $q
                ->where('name', 'like', "%{$request->search}%"))
            ->when($request->filled('programme_id'), fn ($q) => $q
                ->where('programme_id', $request->programme_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $faculties      = DB::connection('test')->table('faculties')->pluck('faculty_name', 'id');
        $departments    = DB::connection('test')->table('departments')->pluck('department_name', 'id');
        $programmeNames = DB::table('programmes')->pluck('name', 'id');

        return view('pages.setup.programme-of-study.index', compact(
            'programmes', 'faculties', 'departments', 'programmeNames'
        ));
    }

    public function create()
    {
        $programmes = $this->programme();
        $faculty    = $this->faculty();

        return view('pages.setup.programme-of-study.create', compact('faculty', 'programmes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'faculty'            => ['required', 'integer'],
            'programme'          => ['required', 'integer'],
            'department_id'      => ['required', 'integer'],
            'duration_full_time' => ['required', 'integer', 'min:1', 'max:4'],
            'duration_part_time' => ['nullable', 'integer', 'min:1', 'max:6'],
        ]);

        $name      = strtoupper($request->name);
        $facultyId = (int) $request->faculty;
        $deptId    = (int) $request->department_id;
        $progId    = (int) $request->programme;

        $duplicate = ProgrammeOfStudy::where('faculty_id', $facultyId)
            ->where('department_id', $deptId)
            ->where('programme_id', $progId)
            ->where('name', $name)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->with('error', 'This programme of study already exists.');
        }

        try {
            DB::transaction(function () use ($request, $name, $facultyId, $deptId, $progId) {
                $programmeId = DB::table('programme_of_studies')->insertGetId([
                    'uuid'          => Str::uuid()->toString(),
                    'faculty_id'    => $facultyId,
                    'department_id' => $deptId,
                    'programme_id'  => $progId,
                    'name'          => $name,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $durations = [
                    ['programme_of_study' => $programmeId, 'programme_type_id' => 1, 'name' => $request->duration_full_time],
                ];

                if ($request->filled('duration_part_time')) {
                    $durations[] = ['programme_of_study' => $programmeId, 'programme_type_id' => 2, 'name' => $request->duration_part_time];
                }

                DB::table('durations')->insert($durations);
            });
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not save programme of study. Please try again.');
        }

        return redirect()
            ->route('setup.programme-of-study.index')
            ->with('success', 'Programme of Study created successfully.');
    }

    public function show(ProgrammeOfStudy $programmeOfStudy)
    {
        $programmeOfStudy->load('durations');

        $faculty    = $this->getFacultyByID($programmeOfStudy->faculty_id);
        $department = $this->getDepartmentById($programmeOfStudy->department_id);
        $programme  = $this->getProgrammeById($programmeOfStudy->programme_id);

        return view('pages.setup.programme-of-study.show', compact(
            'programmeOfStudy', 'faculty', 'department', 'programme'
        ));
    }

    public function edit(ProgrammeOfStudy $programmeOfStudy)
    {
        $programmeOfStudy->load('durations');
        $programmes = $this->programme();
        $faculty    = $this->faculty();

        return view('pages.setup.programme-of-study.edit', compact(
            'programmeOfStudy', 'programmes', 'faculty'
        ));
    }

    public function update(Request $request, ProgrammeOfStudy $programmeOfStudy)
    {
        $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'faculty'            => ['required', 'integer'],
            'programme'          => ['required', 'integer'],
            'department_id'      => ['required', 'integer'],
            'duration_full_time' => ['required', 'integer', 'min:1', 'max:4'],
            'duration_part_time' => ['nullable', 'integer', 'min:1', 'max:6'],
        ]);

        $name      = strtoupper($request->name);
        $facultyId = (int) $request->faculty;
        $deptId    = (int) $request->department_id;
        $progId    = (int) $request->programme;

        $duplicate = ProgrammeOfStudy::where('faculty_id', $facultyId)
            ->where('department_id', $deptId)
            ->where('programme_id', $progId)
            ->where('name', $name)
            ->where('id', '!=', $programmeOfStudy->id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->with('error', 'Another programme of study with these details already exists.');
        }

        try {
            DB::transaction(function () use ($request, $programmeOfStudy, $name, $facultyId, $deptId, $progId) {
                $programmeOfStudy->update([
                    'name'          => $name,
                    'faculty_id'    => $facultyId,
                    'department_id' => $deptId,
                    'programme_id'  => $progId,
                    'updated_at'    => now(),
                ]);

                DB::table('durations')->updateOrInsert(
                    ['programme_of_study' => $programmeOfStudy->id, 'programme_type_id' => 1],
                    ['name' => $request->duration_full_time, 'deleted_at' => null]
                );

                DB::table('durations')->updateOrInsert(
                    ['programme_of_study' => $programmeOfStudy->id, 'programme_type_id' => 2],
                    ['name' => $request->duration_part_time, 'deleted_at' => null]
                );
            });
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Could not update programme of study. Please try again.');
        }

        return redirect()
            ->route('setup.programme-of-study.index')
            ->with('success', 'Programme of Study updated successfully.');
    }

    public function destroy(ProgrammeOfStudy $programmeOfStudy)
    {
        try {
            DB::transaction(function () use ($programmeOfStudy) {
                $programmeOfStudy->durations()->delete();
                $programmeOfStudy->delete();
            });
        } catch (\Throwable $e) {
            return redirect()
                ->route('setup.programme-of-study.index')
                ->with('error', 'Could not delete programme of study. Please try again.');
        }

        return redirect()
            ->route('setup.programme-of-study.index')
            ->with('success', 'Programme of Study deleted successfully.');
    }
}
