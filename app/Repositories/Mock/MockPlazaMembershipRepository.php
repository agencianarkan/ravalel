<?php

namespace App\Repositories\Mock;

use App\Data\PlazaMembership;
use App\Repositories\Contracts\PlazaMembershipRepositoryInterface;

class MockPlazaMembershipRepository implements PlazaMembershipRepositoryInterface
{
    private array $memberships = [];
    private int $nextId = 1;

    public function __construct()
    {
        $this->initializeMockData();
    }

    private function initializeMockData(): void
    {
        // Usuario 1 (owner) es dueño de tienda 1 y 2
        $this->memberships[1] = new PlazaMembership(
            id: 1,
            userId: 1,
            storeId: 1,
            roleId: 1, // owner
            isCustomMode: false,
            invitedBy: null,
            createdAt: new \DateTime('2024-01-01 10:00:00')
        );

        $this->memberships[2] = new PlazaMembership(
            id: 2,
            userId: 1,
            storeId: 2,
            roleId: 1, // owner
            isCustomMode: false,
            invitedBy: null,
            createdAt: new \DateTime('2024-01-15 10:00:00')
        );

        // Usuario 2 (manager) es shop_manager en tienda 1
        $this->memberships[3] = new PlazaMembership(
            id: 3,
            userId: 2,
            storeId: 1,
            roleId: 2, // shop_manager
            isCustomMode: false,
            invitedBy: 1,
            createdAt: new \DateTime('2024-01-10 10:00:00')
        );

        // Usuario 2 es owner de tienda 3
        $this->memberships[4] = new PlazaMembership(
            id: 4,
            userId: 2,
            storeId: 3,
            roleId: 1, // owner
            isCustomMode: false,
            invitedBy: null,
            createdAt: new \DateTime('2024-02-01 10:00:00')
        );

        // Usuario 3 (logistics) en tienda 1 con modo NORMAL
        $this->memberships[5] = new PlazaMembership(
            id: 5,
            userId: 3,
            storeId: 1,
            roleId: 3, // logistics
            isCustomMode: false,
            invitedBy: 1,
            createdAt: new \DateTime('2024-01-20 10:00:00')
        );

        // Usuario 4 (editor) en tienda 1 con modo CUSTOM (tiene overrides)
        $this->memberships[6] = new PlazaMembership(
            id: 6,
            userId: 4,
            storeId: 1,
            roleId: 4, // editor
            isCustomMode: true, // MODO CUSTOM
            invitedBy: 1,
            createdAt: new \DateTime('2024-01-25 10:00:00')
        );

        $this->nextId = 7;
    }

    public function findById(int $id): ?PlazaMembership
    {
        return $this->memberships[$id] ?? null;
    }

    public function findByUserAndStore(int $userId, int $storeId): ?PlazaMembership
    {
        foreach ($this->memberships as $membership) {
            if ($membership->userId === $userId && $membership->storeId === $storeId) {
                return $membership;
            }
        }
        return null;
    }

    public function findByUserId(int $userId): array
    {
        return array_filter(
            $this->memberships,
            fn($m) => $m->userId === $userId
        );
    }

    public function findByStoreId(int $storeId): array
    {
        return array_filter(
            $this->memberships,
            fn($m) => $m->storeId === $storeId
        );
    }

    public function create(array $data): PlazaMembership
    {
        $membership = new PlazaMembership(
            id: $this->nextId++,
            userId: $data['user_id'],
            storeId: $data['store_id'],
            roleId: $data['role_id'],
            isCustomMode: $data['is_custom_mode'] ?? false,
            invitedBy: $data['invited_by'] ?? null,
            createdAt: new \DateTime()
        );

        $this->memberships[$membership->id] = $membership;
        return $membership;
    }

    public function update(int $id, array $data): ?PlazaMembership
    {
        if (!isset($this->memberships[$id])) {
            return null;
        }

        $membership = $this->memberships[$id];
        $updatedMembership = new PlazaMembership(
            id: $membership->id,
            userId: $data['user_id'] ?? $membership->userId,
            storeId: $data['store_id'] ?? $membership->storeId,
            roleId: $data['role_id'] ?? $membership->roleId,
            isCustomMode: $data['is_custom_mode'] ?? $membership->isCustomMode,
            invitedBy: $data['invited_by'] ?? $membership->invitedBy,
            createdAt: $membership->createdAt
        );

        $this->memberships[$id] = $updatedMembership;
        return $updatedMembership;
    }

    public function delete(int $id): bool
    {
        if (isset($this->memberships[$id])) {
            unset($this->memberships[$id]);
            return true;
        }
        return false;
    }
}

