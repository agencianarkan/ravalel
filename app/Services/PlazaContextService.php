<?php

namespace App\Services;

use App\Data\PlazaStore;
use App\Data\PlazaUser;
use App\Repositories\Contracts\PlazaStoreRepositoryInterface;
use App\Repositories\Contracts\PlazaMembershipRepositoryInterface;
use Illuminate\Support\Facades\Session;

class PlazaContextService
{
    private const SESSION_STORE_KEY = 'plaza_current_store_id';
    private const SESSION_USER_KEY = 'plaza_current_user_id';

    public function __construct(
        private PlazaStoreRepositoryInterface $storeRepository,
        private PlazaMembershipRepositoryInterface $membershipRepository
    ) {
    }

    /**
     * Establecer tienda activa en sesión
     */
    public function setCurrentStore(int $storeId, int $userId): bool
    {
        // Verificar que el usuario tenga membresía en esta tienda
        $membership = $this->membershipRepository->findByUserAndStore($userId, $storeId);

        if (!$membership) {
            return false;
        }

        Session::put(self::SESSION_STORE_KEY, $storeId);
        Session::put(self::SESSION_USER_KEY, $userId);

        return true;
    }

    /**
     * Obtener tienda activa actual
     */
    public function getCurrentStore(): ?PlazaStore
    {
        $storeId = Session::get(self::SESSION_STORE_KEY);

        if (!$storeId) {
            return null;
        }

        return $this->storeRepository->findById($storeId);
    }

    /**
     * Obtener ID de tienda activa
     */
    public function getCurrentStoreId(): ?int
    {
        return Session::get(self::SESSION_STORE_KEY);
    }

    /**
     * Obtener ID de usuario actual
     */
    public function getCurrentUserId(): ?int
    {
        return Session::get(self::SESSION_USER_KEY);
    }

    /**
     * Limpiar contexto (logout)
     */
    public function clearContext(): void
    {
        Session::forget(self::SESSION_STORE_KEY);
        Session::forget(self::SESSION_USER_KEY);
    }

    /**
     * Obtener todas las tiendas del usuario
     */
    public function getUserStores(int $userId): array
    {
        $memberships = $this->membershipRepository->findByUserId($userId);
        $stores = [];

        foreach ($memberships as $membership) {
            $store = $this->storeRepository->findById($membership->storeId);
            if ($store) {
                $stores[] = $store;
            }
        }

        return $stores;
    }
}

