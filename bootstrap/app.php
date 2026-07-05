<?php

use App\Http\Middleware\CitizenMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'prevent-back-history' => PreventBackHistoryMiddleware::class,
            'citizenMiddleware' => CitizenMiddleware::class,
            'staffMiddleware' => StaffMiddleware::class,
            'adminMiddleware' => AdminMiddleware::class,
        ]);

        $middleware->web(append: [
            PreventBackHistoryMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            return match ($request->user()?->role) {
                'admin' => route('admin.dashboard'),
                'staff' => route('staff.dashboard'),
                default => route('citizen.dashboard'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );
    })->create();
