<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/setup.php';
require __DIR__.'/admission.php';
require __DIR__.'/courses.php';
require __DIR__.'/general.php';
require __DIR__.'/settings.php';
