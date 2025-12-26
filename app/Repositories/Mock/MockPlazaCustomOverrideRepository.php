<?php

namespace App\Repositories\Mock;

use App\Data\PlazaCustomOverride;
use App\Repositories\Contracts\PlazaCustomOverrideRepositoryInterface;

class MockPlazaCustomOverrideRepository implements PlazaCustomOverrideRepositoryInterface
{
    private array $overrides = [];
    private int $nextId = 1;

    public function __construct()
    {
        $this->initializeMockData();
    }

    private function initializeMockData(): void
    {
        // Usuario 4 (editor) en tienda 1 tiene membership_id = 6
        // Le damos permisos extra que normalmente no tiene un editor:
        
        // Puede ver pedidos (capability_id = 4: orders.view)
        $this->overrides[1] = new PlazaCustomOverride(
            id: 1,
            membershipId: 6,
            capabilityId: 4, // orders.view
            isGranted: true
        );

        // Puede gestionar cupones (capability_id = 11: coupons.manage)
        $this->overrides[2] = new PlazaCustomOverride(
            id: 2,
            membershipId: 6,
            capabilityId: 11, // coupons.manage
            isGranted: true
        );

        // Pero NO puede gestionar reembolsos (capability_id = 7: orders.refund)
        $this->overrides[3] = new PlazaCustomOverride(
            id: 3,
            membershipId: 6,
            capabilityId: 7, // orders.refund
            isGranted: false // Explícitamente denegado
        );

        $this->nextId = 4;
    }

    public function findById(int $id): ?PlazaCustomOverride
    {
        return $this->overrides[$id] ?? null;
    }

    public function findByMembershipId(int $membershipId): array
    {
        return array_filter(
            $this->overrides,
            fn($override) => $override->membershipId === $membershipId
        );
    }

    public function findByMembershipAndCapability(int $membershipId, int $capabilityId): ?PlazaCustomOverride
    {
        foreach ($this->overrides as $override) {
            if ($override->membershipId === $membershipId && $override->capabilityId === $capabilityId) {
                return $override;
            }
        }
        return null;
    }

    public function create(array $data): PlazaCustomOverride
    {
        $override = new PlazaCustomOverride(
            id: $this->nextId++,
            membershipId: $data['membership_id'],
            capabilityId: $data['capability_id'],
            isGranted: $data['is_granted']
        );

        $this->overrides[$override->id] = $override;
        return $override;
    }

    public function update(int $id, array $data): ?PlazaCustomOverride
    {
        if (!isset($this->overrides[$id])) {
            return null;
        }

        $override = $this->overrides[$id];
        $updatedOverride = new PlazaCustomOverride(
            id: $override->id,
            membershipId: $data['membership_id'] ?? $override->membershipId,
            capabilityId: $data['capability_id'] ?? $override->capabilityId,
            isGranted: $data['is_granted'] ?? $override->isGranted
        );

        $this->overrides[$id] = $updatedOverride;
        return $updatedOverride;
    }

    public function delete(int $id): bool
    {
        if (isset($this->overrides[$id])) {
            unset($this->overrides[$id]);
            return true;
        }
        return false;
    }

    public function deleteByMembership(int $membershipId): bool
    {
        $deleted = false;
        foreach ($this->overrides as $key => $override) {
            if ($override->membershipId === $membershipId) {
                unset($this->overrides[$key]);
                $deleted = true;
            }
        }
        return $deleted;
    }
}

