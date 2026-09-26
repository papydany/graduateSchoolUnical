<?php

namespace App\Imports;

use App\Models\ProgrammeOfStudy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgrammeOfStudyImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;

    public int $skipped = 0;

    /** @var array<int, string> */
    public array $errors = [];

    public function __construct(
        private readonly int $facultyId,
        private readonly int $departmentId,
        private readonly int $programmeId,
        private readonly int $duration_full_time,
        private readonly ?int $duration_part_time = null,
    ) {}

    public function collection(Collection $rows): void
    {
        $seen = [];
        $rowNum = 1;

        foreach ($rows as $row) {
            $rowNum++;

            $name = strtoupper(trim((string) ($row['name'] ?? $row['name'] ?? '')));

            if ($name === '') {
                continue;
            }

            $dupKey = $this->facultyId.'|'.$this->departmentId.'|'.$this->programmeId.'|'.$name;

            $duplicate = isset($seen[$dupKey]) || ProgrammeOfStudy::where('faculty_id', $this->facultyId)
                ->where('department_id', $this->departmentId)
                ->where('programme_id', $this->programmeId)
                ->where('name', $name)
                ->exists();

            if ($duplicate) {
                $this->skipped++;

                continue;
            }

            $seen[$dupKey] = true;

            try {
                DB::transaction(function () use ($name) {
                    $programmeOfStudyId = DB::table('programme_of_studies')->insertGetId([
                        'uuid' => Str::uuid()->toString(),
                        'faculty_id' => $this->facultyId,
                        'department_id' => $this->departmentId,
                        'programme_id' => $this->programmeId,
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('durations')->insert([
                        'programme_of_study' => $programmeOfStudyId,
                        'programme_type_id' => 1, // Full-time
                        'name' => $this->duration_full_time,
                    ]);
                    if ($this->duration_part_time) {
                        DB::table('durations')->insert([
                            'programme_of_study' => $programmeOfStudyId,
                            'programme_type_id' => 2, // part-time
                            'name' => $this->duration_part_time,
                        ]);
                    }
                });

                $this->created++;
            } catch (\Throwable $e) {
                $this->errors[] = "Row {$rowNum}: could not save \"{$name}\".";
            }
        }
    }
}
