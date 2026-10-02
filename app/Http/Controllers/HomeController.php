<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Programme;
use App\Models\ProgrammeOfStudy;

class HomeController extends Controller
{
    public function index()
    {
        $programmes = Programme::withCount('programmeOfStudies')->orderBy('id')->get();

        $faculties = Faculty::withCount('departments')
            ->orderByDesc('departments_count')
            ->orderBy('faculty_name')
            ->get();

        $stats = [
            'faculties' => $faculties->count(),
            'departments' => $faculties->sum('departments_count'),
            'programmes_of_study' => ProgrammeOfStudy::count(),
        ];

        return view('welcome', [
            'programmes' => $programmes,
            'featuredFaculties' => $faculties->take(6),
            'stats' => $stats,
        ]);
    }
}
