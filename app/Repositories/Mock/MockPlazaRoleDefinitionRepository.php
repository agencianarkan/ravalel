<?php

namespace App\Repositories\Mock;

use App\Data\PlazaRoleDefinition;
use App\Repositories\Contracts\PlazaRoleDefinitionRepositoryInterface;

class MockPlazaRoleDefinitionRepository implements PlazaRoleDefinitionRepositoryInterface
{
    private array $definitions = [];
    private int $nextId = 1;

    public function __construct()
    {
        $this->initializeMockData();
    }

    private function initializeMockData(): void
    {
        // Owner tiene TODOS los permisos
        $allCapabilities = range(1, 15); // IDs 1-15 de capabilities
        foreach ($allCapabilities as $capId) {
            $this->definitions[$this->nextId++] = new PlazaRoleDefinition(
                id: $this->nextId - 1,
                roleId: 1, // owner
                capabilityId: $capId
            );
        }

        // Shop Manager: casi todo excepto configuración delicada
        $shopManagerCaps = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]; // Sin settings.manage, users.manage
        foreach ($shopManagerCaps as $capId) {
            $this->definitions[$this->nextId++] = new PlazaRoleDefinition(
                id: $this->nextId - 1,
                roleId: 2, // shop_manager
                capabilityId: $capId
            );
        }

        // Logistics: solo pedidos y envíos
        $logisticsCaps = [4, 5, 6, 7, 8]; // orders.view, orders.manage, orders.tracking, orders.refund, customers.view
        foreach ($logisticsCaps as $capId) {
            $this->definitions[$this->nextId++] = new PlazaRoleDefinition(
                id: $this->nextId - 1,
                roleId: 3, // logistics
                capabilityId: $capId
            );
        }

        // Editor: solo productos y media
        $editorCaps = [1, 2, 3]; // products.view, products.manage, stock.manage
        foreach ($editorCaps as $capId) {
            $this->definitions[$this->nextId++] = new PlazaRoleDefinition(
                id: $this->nextId - 1,
                roleId: 4, // editor
                capabilityId: $capId
            );
        }
    }

    public function findByRoleId(int $roleId): array
    {
        return array_filter(
            $this->definitions,
            fn($def) => $def->roleId === $roleId
        );
    }

    public function findByCapabilityId(int $capabilityId): array
    {
        return array_filter(
            $this->definitions,
            fn($def) => $def->capabilityId === $capabilityId
        );
    }

    public function findByRoleAndCapability(int $roleId, int $capabilityId): ?PlazaRoleDefinition
    {
        foreach ($this->definitions as $def) {
            if ($def->roleId === $roleId && $def->capabilityId === $capabilityId) {
                return $def;
            }
        }
        return null;
    }

    public function all(): array
    {
        return array_values($this->definitions);
    }
}

