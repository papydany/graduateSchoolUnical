<?php

use App\Http\Controllers\Students\StudentAuthController;
use App\Http\Controllers\Students\StudentDashboardController;
use App\Http\Controllers\Students\StudentProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:student')->group(function () {
    Route::get('student/login', [StudentAuthController::class, 'index'])
        ->name('student.login');
    Route::post('student/login', [StudentAuthController::class, 'store'])
        ->name('student.login.store');
});

Route::get('student/profile', [StudentProfileController::class, 'index'])
    ->name('student.profile');
Route::post('student/profile', [StudentProfileController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('student.profile.store');

Route::middleware('auth:student')->group(function () {
    Route::get('student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');
    Route::post('student/logout', [StudentAuthController::class, 'destroy'])
        ->name('student.logout');
});
