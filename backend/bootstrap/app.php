<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->api(prepend: HandleCors::class);   // هذا السطر المهم
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();