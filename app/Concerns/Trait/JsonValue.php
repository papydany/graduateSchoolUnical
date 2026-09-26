<?php
namespace App\Concerns\Trait;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Programme;
trait JsonValue
{
    //
    
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

     public function getDepartmentByFacultyReturnJson($id)
    {
        $d = DB::connection('test')
        ->table('departments')
        ->where('faculty_id', $id)
        ->orderBy('department_name', 'ASC')
        ->get();
        return response()->json($d);
    }

      public function getLga($id)
    {
        $d = DB::connection('test')
        ->table('lgas')
        ->where('state_id', $id)
        ->orderBy('lga_name', 'ASC')
        ->get();
        return response()->json($d);
    }
}
