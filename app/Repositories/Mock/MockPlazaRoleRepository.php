<?php

namespace App\Repositories\Mock;

use App\Data\PlazaRole;
use App\Repositories\Contracts\PlazaRoleRepositoryInterface;

class MockPlazaRoleRepository implements PlazaRoleRepositoryInterface
{
    private array $roles = [];

    public function __construct()
    {
        $this->initializeMockData();
    }

    private function initializeMockData(): void
    {
        $this->roles = [
            1 => new PlazaRole(
                id: 1,
                slug: 'owner',
                name: 'Administrador (Dueño)',
                description: 'Acceso total y control de facturación.',
                isCustomizable: false
            ),
            2 => new PlazaRole(
                id: 2,
                slug: 'shop_manager',
                name: 'Shop Manager',
                description: 'Gestión completa de tienda sin acceso a configuración delicada.',
                isCustomizable: true
            ),
            3 => new PlazaRole(
                id: 3,
                slug: 'logistics',
                name: 'Logística / Bodega',
                description: 'Solo gestión de pedidos y envíos.',
                isCustomizable: true
            ),
            4 => new PlazaRole(
                id: 4,
                slug: 'editor',
                name: 'Editor de Catálogo',
                description: 'Solo gestión de productos y media.',
                isCustomizable: true
            ),
        ];
    }

    public function findById(int $id): ?PlazaRole
    {
        return $this->roles[$id] ?? null;
    }

    public function findBySlug(string $slug): ?PlazaRole
    {
        foreach ($this->roles as $role) {
            if ($role->slug === $slug) {
                return $role;
            }
        }
        return null;
    }

    public function all(): array
    {
        return array_values($this->roles);
    }
}

