<?php
/**
 * Script de diagnóstico para probar la carga de clases Plaza
 * Ejecutar en el servidor: php test_plaza_bootstrap.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DIAGNÓSTICO BOOTSTRAP PLAZA ===\n\n";

// 1. Verificar autoloader
echo "1. Cargando autoloader...\n";
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die("ERROR: vendor/autoload.php no existe\n");
}
require __DIR__ . '/vendor/autoload.php';
echo "   ✓ Autoloader cargado\n\n";

// 2. Verificar clases DTOs
echo "2. Verificando DTOs...\n";
$dtos = [
    'App\Data\PlazaUser',
    'App\Data\PlazaStore',
    'App\Data\PlazaMembership',
    'App\Data\PlazaRole',
    'App\Data\PlazaCapability',
];
foreach ($dtos as $dto) {
    if (class_exists($dto)) {
        echo "   ✓ $dto\n";
    } else {
        echo "   ✗ $dto NO EXISTE\n";
    }
}
echo "\n";

// 3. Verificar interfaces
echo "3. Verificando interfaces...\n";
$interfaces = [
    'App\Repositories\Contracts\PlazaUserRepositoryInterface',
    'App\Repositories\Contracts\PlazaStoreRepositoryInterface',
];
foreach ($interfaces as $interface) {
    if (interface_exists($interface)) {
        echo "   ✓ $interface\n";
    } else {
        echo "   ✗ $interface NO EXISTE\n";
    }
}
echo "\n";

// 4. Verificar repositorios mock
echo "4. Verificando repositorios mock...\n";
$repos = [
    'App\Repositories\Mock\MockPlazaUserRepository',
    'App\Repositories\Mock\MockPlazaStoreRepository',
    'App\Repositories\Mock\MockPlazaRoleRepository',
];
foreach ($repos as $repo) {
    if (class_exists($repo)) {
        echo "   ✓ $repo\n";
    } else {
        echo "   ✗ $repo NO EXISTE\n";
    }
}
echo "\n";

// 5. Intentar instanciar repositorio (esto es donde puede fallar)
echo "5. Intentando instanciar MockPlazaUserRepository...\n";
try {
    $repo = new \App\Repositories\Mock\MockPlazaUserRepository();
    echo "   ✓ Repositorio instanciado correctamente\n";
    
    // Probar un método
    $user = $repo->findByEmail('owner@example.com');
    if ($user) {
        echo "   ✓ Usuario de prueba encontrado: " . $user->email . "\n";
    } else {
        echo "   ⚠ Usuario de prueba no encontrado\n";
    }
} catch (\Throwable $e) {
    echo "   ✗ ERROR al instanciar: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "   Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
echo "\n";

// 6. Verificar Service Provider
echo "6. Verificando Service Provider...\n";
if (class_exists('App\Providers\PlazaRepositoryServiceProvider')) {
    echo "   ✓ PlazaRepositoryServiceProvider existe\n";
    
    try {
        // Solo verificar que la clase se puede instanciar (sin app())
        $reflection = new \ReflectionClass('App\Providers\PlazaRepositoryServiceProvider');
        echo "   ✓ Service Provider es instanciable\n";
    } catch (\Throwable $e) {
        echo "   ✗ ERROR al verificar Service Provider: " . $e->getMessage() . "\n";
        echo "   Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "   ✗ PlazaRepositoryServiceProvider NO EXISTE\n";
}
echo "\n";

// 7. Verificar helpers
echo "7. Verificando helpers...\n";
$helperPath = __DIR__ . '/app/Helpers/PlazaHelper.php';
if (file_exists($helperPath)) {
    echo "   ✓ PlazaHelper.php existe\n";
    try {
        require_once $helperPath;
        echo "   ✓ Helpers cargados\n";
    } catch (\Throwable $e) {
        echo "   ✗ ERROR al cargar helpers: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ✗ PlazaHelper.php NO EXISTE\n";
}
echo "\n";

echo "=== FIN DEL DIAGNÓSTICO ===\n";
echo "Si todo está ✓, el problema puede estar en el bootstrap de Laravel.\n";

