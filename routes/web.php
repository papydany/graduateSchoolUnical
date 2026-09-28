<?php

use App\Http\Controllers\Admission\StudentController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\RegisteredCourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Setup\DepartmentAssignedController;
use App\Http\Controllers\Setup\FacultyAssignedController;
use App\Http\Controllers\Setup\ProgrammeOfStudyController;
use App\Http\Controllers\Setup\SpecializationController;
use App\Http\Controllers\Setup\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Setup
    Route::prefix('setup')->name('setup.')->group(function () {
        //  Route::middleware('role:admin')->group(function () {
        Route::get('programme-of-study/import', [ProgrammeOfStudyController::class, 'importForm'])
            ->name('programme-of-study.import.form');
        Route::post('programme-of-study/import', [ProgrammeOfStudyController::class, 'import'])
            ->name('programme-of-study.import');
        Route::get('programme-of-study/sample', [ProgrammeOfStudyController::class, 'downloadSample'])
            ->name('programme-of-study.sample');
        Route::get('programme-of-study/faculties/{facultyId}/departments', [ProgrammeOfStudyController::class, 'getDepartments'])
            ->name('programme-of-study.departments');
        Route::resource('programme-of-study', ProgrammeOfStudyController::class)
            ->names('programme-of-study');
        Route::get('specialization/import', [SpecializationController::class, 'importForm'])
            ->name('specialization.import.form');
        Route::post('specialization/import', [SpecializationController::class, 'import'])
            ->name('specialization.import');
        Route::get('specialization/template', [SpecializationController::class, 'downloadTemplate'])
            ->name('specialization.template');
        Route::get('specialization/faculties/{facultyId}/departments', [SpecializationController::class, 'getDepartments'])
            ->name('specialization.departments');
        Route::resource('specialization', SpecializationController::class)
            ->names('specialization');
        Route::resource('users', UserController::class)->names('users');
        Route::get('department-assigned', [DepartmentAssignedController::class, 'index'])
            ->name('department-assigned.index');
        Route::get('department-assigned/create', [DepartmentAssignedController::class, 'create'])
            ->name('department-assigned.create');
        Route::get('department-assigned/coordinators', [DepartmentAssignedController::class, 'coordinators'])
            ->name('department-assigned.coordinators');
        Route::post('department-assigned', [DepartmentAssignedController::class, 'store'])
            ->name('department-assigned.store');
        Route::get('faculty-assigned', [FacultyAssignedController::class, 'index'])
            ->name('faculty-assigned.index');
        Route::get('faculty-assigned/create', [FacultyAssignedController::class, 'create'])
            ->name('faculty-assigned.create');
        Route::post('faculty-assigned', [FacultyAssignedController::class, 'store'])
            ->name('faculty-assigned.store');
        Route::delete('faculty-assigned/{facultyAssigned}', [FacultyAssignedController::class, 'destroy'])
            ->name('faculty-assigned.destroy');
        // });
    });

    // Admission
    Route::prefix('admission')->name('admission.')->group(function () {
        Route::get('students', [StudentController::class, 'index'])
            ->name('students.index');
        Route::get('students/import', [StudentController::class, 'importForm'])
            ->name('students.import.form');
        Route::post('students/import', [StudentController::class, 'import'])
            ->name('students.import');
        Route::get('students/template', [StudentController::class, 'downloadTemplate'])
            ->name('students.template');
    });

    // Courses
    Route::get('courses/import', [CourseController::class, 'importForm'])
        ->name('courses.import.form');
    Route::post('courses/import', [CourseController::class, 'import'])
        ->name('courses.import');
    Route::get('courses/template', [CourseController::class, 'downloadTemplate'])
        ->name('courses.template');
    Route::get('courses/faculties/{facultyId}/departments', [CourseController::class, 'getDepartments'])
        ->name('courses.departments');
    Route::resource('courses', CourseController::class)->names('courses');

    Route::get('registered-courses/list', [RegisteredCourseController::class, 'list'])
        ->name('registered-courses.list');
    Route::post('registered-courses/register-many', [RegisteredCourseController::class, 'storeMany'])
        ->name('registered-courses.store-many');
    Route::get('registered-courses/filter-options', [RegisteredCourseController::class, 'filterOptions'])
        ->name('registered-courses.filter-options');
    Route::get('registered-courses/get-courses', [RegisteredCourseController::class, 'getCourses'])
        ->name('registered-courses.getCourses');
    Route::get('registered-courses/programme-of-studies', [RegisteredCourseController::class, 'getProgrammeOfStudies'])
        ->name('registered-courses.programme-of-studies');
    Route::get('registered-courses/specializations', [RegisteredCourseController::class, 'getSpecializations'])
        ->name('registered-courses.specializations');
        
    Route::resource('registered-courses', RegisteredCourseController::class)->names('registered-courses');

    // General AJAX routesS
    Route::prefix('general')->name('general.')->group(function () {

        // AJAX: departments by faculty
        Route::get('departments/{facultyId}', [GeneralController::class, 'getDepartments'])
            ->name('departments.by-faculty');
    });
});

require __DIR__.'/settings.php';
