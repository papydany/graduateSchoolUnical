<?php

use App\Http\Controllers\Admission\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admission')->name('admission.')->group(function () {
    Route::get('students', [StudentController::class, 'index'])
        ->name('students.index');
    Route::get('students/import', [StudentController::class, 'importForm'])
        ->name('students.import.form');
    Route::post('students/import', [StudentController::class, 'import'])
        ->name('students.import');
    Route::get('students/template', [StudentController::class, 'downloadTemplate'])
        ->name('students.template');
});
