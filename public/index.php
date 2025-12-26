<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// #region agent log
$logPath = __DIR__ . '/../.cursor/debug.log';
$logData = json_encode([
    'sessionId' => 'debug-session',
    'runId' => 'run1',
    'hypothesisId' => 'A',
    'location' => 'public/index.php:8',
    'message' => 'Request received at index.php',
    'data' => [
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'unknown'
    ],
    'timestamp' => (int)(microtime(true) * 1000)
]) . "\n";
file_put_contents($logPath, $logData, FILE_APPEND);
// #endregion

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';

// #region agent log
$logData2 = json_encode([
    'sessionId' => 'debug-session',
    'runId' => 'run1',
    'hypothesisId' => 'A',
    'location' => 'public/index.php:25',
    'message' => 'About to handle request',
    'data' => ['uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'],
    'timestamp' => (int)(microtime(true) * 1000)
]) . "\n";
file_put_contents($logPath, $logData2, FILE_APPEND);
// #endregion

$request = Request::capture();
$response = $app->handleRequest($request);

// #region agent log
$logData3 = json_encode([
    'sessionId' => 'debug-session',
    'runId' => 'run1',
    'hypothesisId' => 'A',
    'location' => 'public/index.php:35',
    'message' => 'Request handled, sending response',
    'data' => [
        'status_code' => $response->getStatusCode(),
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
    ],
    'timestamp' => (int)(microtime(true) * 1000)
]) . "\n";
file_put_contents($logPath, $logData3, FILE_APPEND);
// #endregion

$response->send();
