<?php

namespace App\Concerns\Trait;
use Illuminate\Support\Facades\DB;
trait General
{
    //

    public function getLga($id)
    {
        $d = DB::connection('test')
        ->table('lgas')
        ->where('state_id', $id)
        ->orderBy('lga_name', 'ASC')
        ->get();
        return response()->json($d);
    }

    public function getDepartmentByFacultyReturnJson($id)
    {
        $d = DB::connection('test')
        ->table('departments')
        ->where('faculty_id', $id)
        ->orderBy('department_name', 'ASC')
        ->get();
        return response()->json($d);
    }

      public function faculty()
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

    public function programme()
    {
        $p = DB::table('programmes')->get();
        return $p;
    }

    public function programmeType()
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
