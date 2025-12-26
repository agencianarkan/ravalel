<?php

namespace App\Helpers;

use App\Services\PlazaContextService;
use App\Services\PlazaPermissionService;

if (!function_exists('plaza_current_store')) {
    /**
     * Obtener tienda activa actual
     */
    function plaza_current_store()
    {
        $contextService = app(PlazaContextService::class);
        return $contextService->getCurrentStore();
    }
}

if (!function_exists('plaza_current_store_id')) {
    /**
     * Obtener ID de tienda activa
     */
    function plaza_current_store_id(): ?int
    {
        $contextService = app(PlazaContextService::class);
        return $contextService->getCurrentStoreId();
    }
}

if (!function_exists('plaza_current_user_id')) {
    /**
     * Obtener ID de usuario actual
     */
    function plaza_current_user_id(): ?int
    {
        $contextService = app(PlazaContextService::class);
        return $contextService->getCurrentUserId();
    }
}

if (!function_exists('plaza_can')) {
    /**
     * Verificar si el usuario actual tiene un permiso en la tienda actual
     */
    function plaza_can(string $capability): bool
    {
        $contextService = app(PlazaContextService::class);
        $permissionService = app(PlazaPermissionService::class);

        $userId = $contextService->getCurrentUserId();
        $storeId = $contextService->getCurrentStoreId();

        if (!$userId || !$storeId) {
            return false;
        }

        return $permissionService->can($userId, $storeId, $capability);
    }
}

if (!function_exists('plaza_user_stores')) {
    /**
     * Obtener todas las tiendas del usuario actual
     */
    function plaza_user_stores(): array
    {
        $contextService = app(PlazaContextService::class);
        $userId = $contextService->getCurrentUserId();

        if (!$userId) {
            return [];
        }

        return $contextService->getUserStores($userId);
    }
}

