<?php

use App\Http\Controllers\Setup\DepartmentAssignedController;
use App\Http\Controllers\Setup\FacultyAssignedController;
use App\Http\Controllers\Setup\ProgrammeOfStudyController;
use App\Http\Controllers\Setup\SpecializationController;
use App\Http\Controllers\Setup\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('setup')->name('setup.')->group(function () {
    //  Route::middleware('role:admin')->group(function () {

    // Programme of Study
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

    // Specialization
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

    // Users
    Route::resource('users', UserController::class)->names('users');

    // Department Assigned
    Route::get('department-assigned', [DepartmentAssignedController::class, 'index'])
        ->name('department-assigned.index');
    Route::get('department-assigned/create', [DepartmentAssignedController::class, 'create'])
        ->name('department-assigned.create');
    Route::get('department-assigned/coordinators', [DepartmentAssignedController::class, 'coordinators'])
        ->name('department-assigned.coordinators');
    Route::post('department-assigned', [DepartmentAssignedController::class, 'store'])
        ->name('department-assigned.store');
    Route::delete('department-assigned/{departmentAssigned}', [DepartmentAssignedController::class, 'destroy'])
        ->name('department-assigned.destroy');

    // Faculty Assigned
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
