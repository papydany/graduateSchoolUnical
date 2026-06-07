<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\Trait\All;
class GeneralController extends Controller
{
     use All;
    //

        /**
     * AJAX: return departments for a given faculty as JSON.
     */
    public function getDepartments(int $facultyId)
    {
    
        return $this->getDepartmentByFacultyReturnJson($facultyId);
    }

}
