<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            $loadApiRoutes = function ($dir) use (&$loadApiRoutes) {
                foreach (glob($dir . '/*.php') as $routeFile) {
                    Route::prefix('api')
                        ->middleware('api')
                        ->group($routeFile);
                }
                foreach (glob($dir . '/*', GLOB_ONLYDIR) as $subDir) {
                    $loadApiRoutes($subDir);
                }
            };

            $loadApiRoutes(__DIR__ . '/../routes/api');

            Route::prefix('api')
                ->middleware('api')
                ->group(__DIR__ . '/../routes/api.php');
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
       /*  $middleware->append(\App\Http\Middleware\Example::class); */
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->withSingletons([
        \Illuminate\Contracts\Debug\ExceptionHandler::class => \App\Exceptions\Handler::class,
    ])
    ->create();
