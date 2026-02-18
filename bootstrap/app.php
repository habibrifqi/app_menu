<?php

use App\Http\Middleware\CanCheck;
use App\Http\Middleware\CanCreateUser;
use App\Http\Middleware\CanOrder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        //  $middleware->statefulApi();
         $middleware->alias([
            'can_check' => CanCheck::class,
            'can_order' => CanOrder::class,
            'Can_create_user' => CanCreateUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
