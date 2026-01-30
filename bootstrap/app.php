<?php

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
          $middleware->alias([
        'agence' => \App\Http\Middleware\Agence::class,
        'agent' => \App\Http\Middleware\Agent::class,
    ]);
    
   /* $middleware->web([
        \App\Http\Middleware\Agence::class,
        \App\Http\Middleware\Agent::class,
    ]);*/
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();


 