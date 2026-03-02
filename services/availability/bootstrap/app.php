<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (!($request->expectsJson() || $request->is('api/*'))) {
                return null;
            }

            $previous = $e->getPrevious();
            $isDbDown = $e instanceof QueryException || $e instanceof \PDOException || $previous instanceof \PDOException;
            $message = strtolower((string) $e->getMessage());
            if ($isDbDown || str_contains($message, 'sqlstate') || str_contains($message, 'getaddrinfo') || str_contains($message, 'connection refused')) {
                return response()->json([
                    'error' => 'service_unavailable',
                    'service' => 'availability',
                ], 503);
            }

            return null;
        });
    })->create();
