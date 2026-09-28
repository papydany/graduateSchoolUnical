<?php

use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\RegisteredCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
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

    // Registered Courses
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
});
