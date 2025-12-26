<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'plaza.store' => \App\Http\Middleware\EnsureStoreContext::class,
            'plaza.permission' => \App\Http\Middleware\CheckPlazaPermission::class,
            'plaza.brute-force' => \App\Http\Middleware\PlazaBruteForceProtection::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Capturar todas las excepciones para debugging
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            // Escribir error a archivo de log
            $logPath = __DIR__ . '/../storage/logs/plaza_debug.log';
            $errorData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => $request->fullUrl() ?? 'N/A',
                'method' => $request->method() ?? 'N/A',
            ];
            @file_put_contents($logPath, json_encode($errorData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
            
            // SIEMPRE mostrar el error para debugging (temporal)
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString())
            ], 500);
        });
        
        // También capturar excepciones no renderizadas
        $exceptions->report(function (\Throwable $e) {
            $logPath = __DIR__ . '/../storage/logs/plaza_debug.log';
            $errorData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'report',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
            @file_put_contents($logPath, json_encode($errorData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
        });
    })->create();
