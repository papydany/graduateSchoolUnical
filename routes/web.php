<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Setup\ProgrammeOfStudyController;
use App\Http\Controllers\Setup\SpecializationController;
use App\Http\Controllers\Setup\UserController;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Setup
    Route::prefix('setup')->name('setup.')->group(function () {
        Route::resource('programme-of-study', ProgrammeOfStudyController::class)
            ->names('programme-of-study');
        Route::resource('specialization', SpecializationController::class)
            ->names('specialization');

        Route::middleware('role:admin')->group(function () {
            Route::resource('users', UserController::class)->names('users');
        });
    });

        // General AJAX routes
    Route::prefix('general')->name('general.')->group(function () {
      

        // AJAX: departments by faculty
        Route::get('departments/{facultyId}', [GeneralController::class, 'getDepartments'])
            ->name('departments.by-faculty');
    });
});

require __DIR__.'/settings.php';
