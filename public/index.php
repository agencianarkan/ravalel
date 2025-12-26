<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    // #region agent log
    $logPath = __DIR__ . '/../.cursor/debug.log';
    @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'E','location'=>'public/index.php:16','message'=>'Laravel app bootstrapped','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
    // #endregion
    
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    // #region agent log
    $logPath = __DIR__ . '/../.cursor/debug.log';
    $errorData = [
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'E',
        'location' => 'public/index.php:25',
        'message' => 'Fatal error in index.php',
        'data' => [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ],
        'timestamp' => time() * 1000
    ];
    @file_put_contents($logPath, json_encode($errorData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);
    // #endregion
    
    // Mostrar error si está en debug
    if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG']) {
        throw $e;
    }
    
    http_response_code(500);
    echo "Internal Server Error";
    exit;
}
