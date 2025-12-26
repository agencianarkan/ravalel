<?php

use App\Http\Controllers\Auth\PlazaAuthController;
use App\Http\Controllers\Auth\PlazaPasswordResetController;
use App\Http\Controllers\PlazaStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirecciones de rutas comunes sin prefijo plaza
Route::get('/login', function () {
    // #region agent log
    $logPath = base_path('.cursor/debug.log');
    $logData = json_encode([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'B',
        'location' => 'routes/web.php:13',
        'message' => 'Login redirect route executed',
        'data' => ['uri' => request()->path()],
        'timestamp' => (int)(microtime(true) * 1000)
    ]) . "\n";
    file_put_contents($logPath, $logData, FILE_APPEND);
    // #endregion
    
    try {
        $redirect = redirect()->route('plaza.login');
        
        // #region agent log
        $logData2 = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'C',
            'location' => 'routes/web.php:25',
            'message' => 'Redirect created successfully',
            'data' => ['target_url' => $redirect->getTargetUrl()],
            'timestamp' => (int)(microtime(true) * 1000)
        ]) . "\n";
        file_put_contents($logPath, $logData2, FILE_APPEND);
        // #endregion
        
        return $redirect;
    } catch (\Exception $e) {
        // #region agent log
        $logData3 = json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'C',
            'location' => 'routes/web.php:35',
            'message' => 'Redirect failed with exception',
            'data' => [
                'error' => $e->getMessage(),
                'route_name' => 'plaza.login'
            ],
            'timestamp' => (int)(microtime(true) * 1000)
        ]) . "\n";
        file_put_contents($logPath, $logData3, FILE_APPEND);
        // #endregion
        
        throw $e;
    }
});

Route::get('/dashboard', function () {
    return redirect()->route('plaza.dashboard');
});

Route::get('/forgot-password', function () {
    return redirect()->route('plaza.password.request');
});

// Rutas de autenticación Plaza
Route::prefix('plaza')->name('plaza.')->group(function () {
    
    // #region agent log
    $logPath = base_path('.cursor/debug.log');
    $logData = json_encode([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'B',
        'location' => 'routes/web.php:45',
        'message' => 'Plaza routes group registering',
        'data' => [],
        'timestamp' => (int)(microtime(true) * 1000)
    ]) . "\n";
    file_put_contents($logPath, $logData, FILE_APPEND);
    // #endregion
    
    // Login
    Route::get('/login', [PlazaAuthController::class, 'showLoginForm'])->name('login');
    
    // #region agent log
    $logData2 = json_encode([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'B',
        'location' => 'routes/web.php:52',
        'message' => 'Plaza login route registered',
        'data' => ['route_name' => 'plaza.login'],
        'timestamp' => (int)(microtime(true) * 1000)
    ]) . "\n";
    file_put_contents($logPath, $logData2, FILE_APPEND);
    // #endregion
    Route::post('/login', [PlazaAuthController::class, 'login'])->middleware('plaza.brute-force');
    
    // Logout
    Route::post('/logout', [PlazaAuthController::class, 'logout'])->name('logout');
    
    // Activación de cuenta
    Route::get('/activate/{token}', [PlazaAuthController::class, 'showActivationForm'])->name('activate');
    Route::post('/activate/{token}', [PlazaAuthController::class, 'activate']);
    
    // Reset de contraseña
    Route::get('/forgot-password', [PlazaPasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PlazaPasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PlazaPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PlazaPasswordResetController::class, 'reset'])->name('password.update');
    
    // Rutas protegidas
    Route::middleware(['plaza.store'])->group(function () {
        Route::get('/dashboard', [PlazaStoreController::class, 'dashboard'])->name('dashboard');
        Route::get('/test-permission/{capability}', [PlazaStoreController::class, 'testPermission'])->name('test.permission');
    });
    
    // Selección de tienda (antes de entrar al dashboard)
    Route::get('/stores/select', [PlazaStoreController::class, 'select'])->name('stores.select');
    Route::post('/stores/{storeId}/set-active', [PlazaStoreController::class, 'setActive'])->name('stores.set-active');
    
    // Rutas de prueba con permisos específicos
    Route::middleware(['plaza.store', 'plaza.permission:orders.view'])->group(function () {
        Route::get('/orders', function () {
            return response()->json(['message' => 'Tienes acceso a ver pedidos']);
        })->name('orders.index');
    });
    
    Route::middleware(['plaza.store', 'plaza.permission:orders.manage'])->group(function () {
        Route::post('/orders/{id}/update', function ($id) {
            return response()->json(['message' => "Pedido {$id} actualizado"]);
        })->name('orders.update');
    });
    
    Route::middleware(['plaza.store', 'plaza.permission:products.manage'])->group(function () {
        Route::get('/products', function () {
            return response()->json(['message' => 'Tienes acceso a gestionar productos']);
        })->name('products.index');
    });
});
