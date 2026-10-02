<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.students.dashboard', [
            'student' => $request->user('student'),
            'studentType' => $request->session()->get('student_type'),
        ]);
    }
}
