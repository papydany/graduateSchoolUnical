<?php

namespace App\Http\Controllers;

use App\Models\ProgrammeOfStudy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Programme statistics
        $totalProgrammes    = ProgrammeOfStudy::count();
        $activeProgrammes   = ProgrammeOfStudy::count();
        $inactiveProgrammes = ProgrammeOfStudy::count();
        $phdProgrammes      = ProgrammeOfStudy::count();

        // Breakdown by degree type
        $degreeBreakdown = ProgrammeOfStudy::get();

        // Last 6 recently added programmes
        $recentProgrammes = ProgrammeOfStudy::latest()->limit(6)->get();

        return view('dashboard', compact(
            'totalProgrammes',
            'activeProgrammes',
            'inactiveProgrammes',
            'phdProgrammes',
            'degreeBreakdown',
            'recentProgrammes',
        ));
    }

  /*  public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'faculty'        => ['required', 'integer'],
            'programme' => ['required', 'integer'],
            'department'  => ['required', 'integer'],
            'duration_full_time'    => ['required', 'integer', 'min:1', 'max:4'],
            'duration_part_time' => ['nullable', 'integer', 'min:1', 'max:6'],
            
        ]);

       // ProgrammeOfStudy::create($validated);

        $faculty=$request->faculty;
        $department=$request->department;
        $programme=$request->programme;
        $name=strtoupper($request->name);
        $durationFullTime=$request->duration_full_time;
        $durationPartTime=$request->duration_part_time;
        $data =["faculty_id"=>$faculty,'department_id'=>$department,'programme_id'=>$programme,'name'=>$name];
       // dd($data);
        $check =ProgrammeOfStudy::where($data)->first();
      
        if($check != null)
        {
         $request->session()->flash('error', 'programme of studies exist already');
         return back(); 
        }

        $id = DB::table('programme_of_studies')->insertGetId($data);
        $data_duration =[["programme_of_study"=>$id,'programme_type_id'=>1,'name'=>$durationFullTime],
        ["programme_of_study"=>$id,'programme_type_id'=>2,'name'=>$durationPartTime]];
      
        $id = DB::table('durations')->insert($data_duration);
       

        return redirect()
            ->route('setup.programme-of-study.index')
            ->with('success', 'Programme of Study created successfully.');
    }*/
}
