<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Repositories\Students\StudentProfileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Throwable;

class StudentProfileService
{
    public const MARITAL_STATUSES = ['Single', 'Married', 'Divorced', 'Widowed'];

    public const GENDERS = ['Male', 'Female'];

    public function __construct(private readonly StudentProfileRepository $repository)
    {
    }

    public function findByRegistrationNumber(string $registrationNumber): ?Student
    {
        return $this->repository->findByRegistrationNumber(trim($registrationNumber));
    }

    /**
     * The student whose profile is being completed, if it is still incomplete.
     */
    public function pendingStudent(?int $studentId): ?Student
    {
        $student = $this->repository->find($studentId);

        return $student?->hasIncompleteProfile() ? $student : null;
    }

    public function getCompleteData(Student $student): array
    {
        return [
            'student' => $student,
            'faculty' => $this->repository->facultyName($student->faculty_id),
            'department' => $this->repository->departmentName($student->department_id),
            'programme' => $this->repository->programmeName($student->programme_id),
            'session' => $this->repository->sessionLabel($student->entry_session),
            'programmesOfStudy' => $this->repository->programmesOfStudyFor($student),
            'specializations' => $this->repository->specializationsFor($student),
            'states' => $this->repository->allStates(),
            'maritalStatuses' => self::MARITAL_STATUSES,
            'genders' => self::GENDERS,
        ];
    }

    public function completeProfile(Student $student, array $validated, UploadedFile $passport): void
    {
        $validated['image_url'] = $passport->store('passports', 'public');
        unset($validated['passport']);

        try {
            $this->repository->completeProfile($student, $validated);
        } catch (Throwable $e) {
            Storage::disk('public')->delete($validated['image_url']);

            throw $e;
        }
    }

    public function lgasForState(int $stateId): Collection
    {
        return $this->repository->lgasForState($stateId);
    }
}
