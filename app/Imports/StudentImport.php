<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Throwable;

class StudentImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsErrors, SkipsFailures;

    public int $imported = 0;

    public int $skipped = 0;

    /** @var list<string> */
    public array $saveErrors = [];

    /** @var array<string, bool> */
    private array $seenRegNumbers = [];

    private ?string $currentRegNumber = null;

    public function __construct(
        private readonly int $facultyId,
        private readonly int $departmentId,
        private readonly int $programmeId,
        private readonly int $entrySession,
        private readonly ?int $uploadedBy,
    ) {}

    // Rows 1-3 hold faculty, department and programme (see StudentTemplateExport)
    public function headingRow(): int
    {
        return 4;
    }

    public function rules(): array
    {
        // "registrationNumber" is slugged to "registrationnumber" by the heading formatter
        return [
            'surname' => ['required', 'max:255'],
            'firstname' => ['required', 'max:255'],
            'othername' => ['nullable', 'max:255'],
            'registrationnumber' => ['required', 'max:50'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'surname.required' => 'Surname is required.',
            'firstname.required' => 'Firstname is required.',
            'registrationnumber.required' => 'Registration number is required.',
        ];
    }

    public function model(array $row)
    {
        $regNumber = strtoupper(trim((string) $row['registrationnumber']));

        $duplicate = isset($this->seenRegNumbers[$regNumber])
            || Student::where('registration_number', $regNumber)->exists();

        if ($duplicate) {
            $this->skipped++;

            return null;
        }

        $this->seenRegNumbers[$regNumber] = true;
        $this->currentRegNumber = $regNumber;
        $this->imported++;

        $othername = trim((string) ($row['othername'] ?? ''));

        return new Student([
            'uuid' => Str::uuid()->toString(),
            'faculty_id' => $this->facultyId,
            'department_id' => $this->departmentId,
            'programme_id' => $this->programmeId,
            'entry_session' => $this->entrySession,
            'surname' => trim((string) $row['surname']),
            'firstname' => trim((string) $row['firstname']),
            'othername' => $othername !== '' ? $othername : null,
            'registration_number' => $regNumber,
            'uploaded_by' => $this->uploadedBy,
            'status' => 23,
        ]);
    }

    /**
     * Called when saving a row fails; the row was already counted as imported.
     */
    public function onError(Throwable $e)
    {
        $this->errors[] = $e;
        $this->imported--;

        $reason = $e instanceof QueryException ? ($e->errorInfo[2] ?? $e->getMessage()) : $e->getMessage();

        $this->saveErrors[] = "Registration number {$this->currentRegNumber}: could not be saved ({$reason}).";
    }
}
