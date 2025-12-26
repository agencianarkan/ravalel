<?php

namespace App\Http\Controllers;

use App\Services\PlazaContextService;
use App\Services\PlazaPermissionService;
use Illuminate\Http\Request;

class PlazaStoreController extends Controller
{
    public function __construct(
        private PlazaContextService $contextService,
        private PlazaPermissionService $permissionService
    ) {}

    /**
     * Seleccionar tienda
     */
    public function select()
    {
        $userId = session('plaza_user_id');

        if (!$userId) {
            return redirect()->route('plaza.login');
        }

        $stores = $this->contextService->getUserStores($userId);

        return view('plaza.stores.select', compact('stores'));
    }

    /**
     * Establecer tienda activa
     */
    public function setActive(Request $request, int $storeId)
    {
        $userId = session('plaza_user_id');

        if (!$userId) {
            return redirect()->route('plaza.login');
        }

        $set = $this->contextService->setCurrentStore($storeId, $userId);

        if (!$set) {
            return back()->with('error', 'No tienes acceso a esta tienda.');
        }

        return redirect()->route('plaza.dashboard')
            ->with('success', 'Tienda seleccionada correctamente.');
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        $store = $this->contextService->getCurrentStore();
        $userId = $this->contextService->getCurrentUserId();

        if (!$store || !$userId) {
            return redirect()->route('plaza.stores.select');
        }

        $permissions = $this->permissionService->getUserPermissionsByModule($userId, $store->id);

        return view('plaza.dashboard', compact('store', 'permissions'));
    }

    /**
     * Probar permisos (endpoint de prueba)
     */
    public function testPermission(Request $request, string $capability)
    {
        $userId = $this->contextService->getCurrentUserId();
        $storeId = $this->contextService->getCurrentStoreId();

        if (!$userId || !$storeId) {
            return response()->json(['error' => 'No hay contexto de tienda'], 400);
        }

        $can = $this->permissionService->can($userId, $storeId, $capability);

        return response()->json([
            'user_id' => $userId,
            'store_id' => $storeId,
            'capability' => $capability,
            'granted' => $can
        ]);
    }
}

