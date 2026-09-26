<?php

namespace App\Concerns\Trait;
use Illuminate\Support\Facades\DB;
trait General
{
    //
 public function levels(): array
    {
        return [1 => 'Year 1', 2 => 'Year 2', 3 => 'Year 3'];
    }

    public function sessionOptions(): array
    {
        $year     = (int) date('Y');
        $sessions = [];
        for ($y = $year + 1; $y >= $year - 4; $y--) {
            $sessions[$y] = "{$y}/" . ($y + 1);
        }
        return $sessions;
    }
    public function getProgrammesOfStudies(int $programmeId, int $facultyId, int $departmentId, array $columns = ['*'])
    {
        return DB::table('programme_of_studies')
            ->where('programme_id', $programmeId)
            ->where('faculty_id', $facultyId)
            ->where('department_id', $departmentId)
            ->orderBy('name')
            ->get($columns);
    }

   

      public function allFaculty()
    {
        $f = DB::connection('test')->table('faculties')
        ->where('status', 0)
        ->orderBy('faculty_name', 'ASC')
        ->get();
        return $f;
    }

    public function getDepartmentByFaculty($id)
    {
        $f = DB::connection('test')->table('departments')
        ->where('faculty_id', $id)
        ->orderBy('department_name', 'ASC')
        ->get();
        return $d;
    }

    public function allProgramme()
    {
        $p = DB::table('programmes')->get();
        return $p;
    }

    public function allProgrammeType()
    {
        $p = DB::table('programme_types')->get();
        return $p;
    }

public function getFacultyByID($id)
{
    return DB::connection('test')
    ->table('faculties')
    ->find($id);
}

public function getDepartmentById($id)
{
return DB::connection('test')
->table('departments')
->find($id);
}
    
public function getProgrammeById($id)
{
    $p = DB::table('programmes')->find($id);
    return $p;
}

public function getProgrammeTypeById($id)
{
    $p = DB::table('programme_types')->find($id);
    return $p;
} 

}
