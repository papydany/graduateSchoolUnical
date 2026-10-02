<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\XSS;
use App\Http\Middleware\ValidSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
          $middleware->alias([
         'role' => CheckRole::class,
          'xss' => XSS::class,
        'vs' => ValidSession::class,
]);

        $middleware->redirectGuestsTo(fn ($request) => $request->is('student/*')
            ? route('student.login')
            : route('login'));
        $middleware->redirectUsersTo(fn ($request) => $request->is('student/*')
            ? route('student.dashboard')
            : route('dashboard'));
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
