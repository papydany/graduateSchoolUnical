<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/setup.php';
require __DIR__.'/admission.php';
require __DIR__.'/courses.php';
require __DIR__.'/general.php';
require __DIR__.'/settings.php';
require __DIR__.'/students/auth.php';