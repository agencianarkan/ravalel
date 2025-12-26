<?php

use App\Http\Controllers\Auth\PlazaAuthController;
use App\Http\Controllers\Auth\PlazaPasswordResetController;
use App\Http\Controllers\PlazaStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación Plaza
Route::prefix('plaza')->name('plaza.')->group(function () {
    // #region agent log
    try {
        $logPath = __DIR__ . '/../.cursor/debug.log';
        @file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'E','location'=>'routes/web.php:13','message'=>'Plaza routes group entry','data'=>[],'timestamp'=>time()*1000])."\n", FILE_APPEND);
    } catch (\Throwable $e) {}
    // #endregion
    
    // Login
    Route::get('/login', [PlazaAuthController::class, 'showLoginForm'])->name('login');
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
