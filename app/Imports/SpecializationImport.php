<?php

namespace App\Imports;

use App\Models\Specialization;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SpecializationImport implements SkipsEmptyRows, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    public int $imported = 0;

    public int $skipped = 0;

    /** @var array<string, bool> */
    private array $seen = [];

    public function __construct(
        private readonly int $facultyId,
        private readonly int $departmentId,
    ) {}

    public function rules(): array
    {
        return [
            'specialization_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function model(array $row)
    {
        $name = strtoupper(trim((string) ($row['specialization_name'] ?? $row['name'] ?? '')));

        $duplicate = isset($this->seen[$name]) || Specialization::where('department_id', $this->departmentId)
            ->where('name', $name)
            ->exists();

        if ($duplicate) {
            $this->skipped++;

            return null;
        }

        $this->seen[$name] = true;
        $this->imported++;

        return new Specialization([
            'faculty_id' => $this->facultyId,
            'department_id' => $this->departmentId,
            'name' => $name,
        ]);
    }
}
