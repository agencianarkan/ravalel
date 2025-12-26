<?php

namespace App\Services;

use App\Data\PlazaCapability;
use App\Data\PlazaMembership;
use App\Repositories\Contracts\PlazaCapabilityRepositoryInterface;
use App\Repositories\Contracts\PlazaCustomOverrideRepositoryInterface;
use App\Repositories\Contracts\PlazaMembershipRepositoryInterface;
use App\Repositories\Contracts\PlazaRoleDefinitionRepositoryInterface;

class PlazaPermissionService
{
    public function __construct(
        private PlazaMembershipRepositoryInterface $membershipRepository,
        private PlazaRoleDefinitionRepositoryInterface $roleDefinitionRepository,
        private PlazaCustomOverrideRepositoryInterface $customOverrideRepository,
        private PlazaCapabilityRepositoryInterface $capabilityRepository
    ) {
    }

    /**
     * Verificar si un usuario tiene un permiso en una tienda específica
     */
    public function can(int $userId, int $storeId, string $capabilitySlug): bool
    {
        // Buscar membresía
        $membership = $this->membershipRepository->findByUserAndStore($userId, $storeId);

        if (!$membership) {
            return false;
        }

        // Buscar capability
        $capability = $this->capabilityRepository->findBySlug($capabilitySlug);

        if (!$capability) {
            return false;
        }

        // Lógica híbrida: verificar si está en modo custom
        if ($membership->isCustomMode) {
            return $this->checkCustomMode($membership, $capability);
        } else {
            return $this->checkRoleMode($membership, $capability);
        }
    }

    /**
     * Verificar permiso en modo personalizado (custom_mode = true)
     */
    private function checkCustomMode(PlazaMembership $membership, PlazaCapability $capability): bool
    {
        // Buscar override específico para esta membresía y capability
        $override = $this->customOverrideRepository->findByMembershipAndCapability(
            $membership->id,
            $capability->id
        );

        // Si existe override, usar ese valor
        if ($override !== null) {
            return $override->isGranted;
        }

        // Si no hay override, usar el permiso del rol base
        return $this->checkRoleMode($membership, $capability);
    }

    /**
     * Verificar permiso en modo rol (custom_mode = false)
     */
    private function checkRoleMode(PlazaMembership $membership, PlazaCapability $capability): bool
    {
        // Buscar si el rol tiene este permiso en las definiciones
        $roleDefinition = $this->roleDefinitionRepository->findByRoleAndCapability(
            $membership->roleId,
            $capability->id
        );

        return $roleDefinition !== null;
    }

    /**
     * Obtener todos los permisos de un usuario en una tienda
     */
    public function getUserPermissions(int $userId, int $storeId): array
    {
        $membership = $this->membershipRepository->findByUserAndStore($userId, $storeId);

        if (!$membership) {
            return [];
        }

        $allCapabilities = $this->capabilityRepository->all();
        $permissions = [];

        foreach ($allCapabilities as $capability) {
            $permissions[$capability->slug] = $this->can($userId, $storeId, $capability->slug);
        }

        return $permissions;
    }

    /**
     * Obtener permisos agrupados por módulo
     */
    public function getUserPermissionsByModule(int $userId, int $storeId): array
    {
        $permissions = $this->getUserPermissions($userId, $storeId);
        $allCapabilities = $this->capabilityRepository->all();
        $grouped = [];

        foreach ($allCapabilities as $capability) {
            if (!isset($grouped[$capability->module])) {
                $grouped[$capability->module] = [];
            }

            $grouped[$capability->module][$capability->slug] = [
                'label' => $capability->label,
                'granted' => $permissions[$capability->slug] ?? false
            ];
        }

        return $grouped;
    }

    /**
     * Agregar override personalizado
     */
    public function addOverride(int $membershipId, int $capabilityId, bool $isGranted): bool
    {
        $existing = $this->customOverrideRepository->findByMembershipAndCapability($membershipId, $capabilityId);

        if ($existing) {
            $this->customOverrideRepository->update($existing->id, [
                'is_granted' => $isGranted
            ]);
        } else {
            $this->customOverrideRepository->create([
                'membership_id' => $membershipId,
                'capability_id' => $capabilityId,
                'is_granted' => $isGranted
            ]);
        }

        return true;
    }

    /**
     * Remover override personalizado
     */
    public function removeOverride(int $membershipId, int $capabilityId): bool
    {
        $override = $this->customOverrideRepository->findByMembershipAndCapability($membershipId, $capabilityId);

        if ($override) {
            return $this->customOverrideRepository->delete($override->id);
        }

        return false;
    }
}

