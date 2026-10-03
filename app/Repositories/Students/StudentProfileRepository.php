<?php

namespace App\Repositories\Students;

use App\Concerns\Trait\General;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentProfileRepository
{
    use General;

    public function findByRegistrationNumber(string $registrationNumber): ?Student
    {
        return Student::where('registration_number', $registrationNumber)->first();
    }

    public function find(?int $id): ?Student
    {
        return $id ? Student::find($id) : null;
    }

    public function facultyName(int $facultyId): ?string
    {
        return DB::connection('test')->table('faculties')->where('id', $facultyId)->value('faculty_name');
    }

    public function departmentName(int $departmentId): ?string
    {
        return DB::connection('test')->table('departments')->where('id', $departmentId)->value('department_name');
    }

    public function programmeName(int $programmeId): ?string
    {
        return DB::table('programmes')->where('id', $programmeId)->value('name');
    }

    public function sessionLabel(int $entrySession): string
    {
        return $this->sessionOptions()[$entrySession] ?? $entrySession.'/'.($entrySession + 1);
    }

    public function programmesOfStudyFor(Student $student): Collection
    {
        return $this->getProgrammesOfStudies($student->programme_id, $student->faculty_id, $student->department_id, ['id', 'name']);
    }

    public function specializationsFor(Student $student): Collection
    {
        return DB::table('specializations')
            ->where('faculty_id', $student->faculty_id)
            ->where('department_id', $student->department_id)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function allStates(): Collection
    {
        return DB::connection('test')->table('states')
            ->orderBy('state_name')
            ->get(['id', 'state_name']);
    }

    public function lgasForState(int $stateId): Collection
    {
        return DB::connection('test')->table('lgas')
            ->where('state_id', $stateId)
            ->orderBy('lga_name')
            ->get(['id', 'lga_name']);
    }

    public function completeProfile(Student $student, array $data): bool
    {
        $student->fill($data);
        $student->status = Student::STATUS_PROFILE_COMPLETED;

        return $student->save();
    }
}
