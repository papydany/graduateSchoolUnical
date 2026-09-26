<?php

namespace App\Imports;

use App\Models\Courses;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CourseImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use SkipsErrors, SkipsFailures;

    public int $imported = 0;

    public int $skipped = 0;

    /** @var array<string, bool> */
    private array $seenCodes = [];

    public function __construct(
        private readonly int $facultyId,
        private readonly int $departmentId,
        private readonly int $programmeId,
    ) {}

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'numeric', 'min:0'],
            'semester' => ['required', 'numeric', Rule::in([1,2])],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'code.required' => 'Course code is required.',
            'title.required' => 'Course title is required.',
            'unit.numeric' => 'Credit unit must be numeric.',
        ];
    }

    public function model(array $row)
    {
        $code = strtoupper(trim((string) $row['code']));
        $title = trim((string) $row['title']);
        $unit = (int) $row['unit'];
        $semester = (int) $row['semester'];
       $uuid = Str::uuid()->toString();
        
        $duplicate = isset($this->seenCodes[$code])
            || Courses::withTrashed()->where('code', $code)->exists();

        if ($duplicate) {
            $this->skipped++;

            return null;
        }

        $this->seenCodes[$code] = true;
        $this->imported++;

        return new Courses([
            'uuid' => $uuid,
            'faculty_id' => $this->facultyId,
            'department_id' => $this->departmentId,
            'programme_id' => $this->programmeId,
            'code' => $code,
            'title' => $title,
            'unit' => $unit,
            'semester' => $semester,
        ]);
    }


   
}
