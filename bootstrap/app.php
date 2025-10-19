<?php

use Illuminate\Foundation\Application;
use App\Exceptions\Renderers\ApiExceptionRenderer;
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
        $middleware->api(append: [
            \App\Http\Middleware\TrackProjectActivity::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Request $request, \Throwable $exception) {
            if ($exception instanceof \App\Exceptions\ApiException) {
                return response()->json([
                    'error' => $exception->getMessage(),
                ], $exception->getStatusCode());
            }
        });
    })->create();
