<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

// General AJAX routes
Route::middleware(['auth', 'verified'])->prefix('general')->name('general.')->group(function () {
    // AJAX: departments by faculty
    Route::get('departments/{facultyId}', [GeneralController::class, 'getDepartments'])
        ->name('departments.by-faculty');
});
