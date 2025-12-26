<?php

namespace App\Repositories\Mock;

use App\Data\PlazaCapability;
use App\Repositories\Contracts\PlazaCapabilityRepositoryInterface;

class MockPlazaCapabilityRepository implements PlazaCapabilityRepositoryInterface
{
    private array $capabilities = [];

    public function __construct()
    {
        $this->initializeMockData();
    }

    private function initializeMockData(): void
    {
        $capabilities = [
            // Inventory
            ['module' => 'inventory', 'slug' => 'products.view', 'label' => 'Ver lista de productos'],
            ['module' => 'inventory', 'slug' => 'products.manage', 'label' => 'Crear/Editar/Borrar productos'],
            ['module' => 'inventory', 'slug' => 'stock.manage', 'label' => 'Editar solamente stock'],
            
            // Orders
            ['module' => 'orders', 'slug' => 'orders.view', 'label' => 'Ver lista de pedidos'],
            ['module' => 'orders', 'slug' => 'orders.manage', 'label' => 'Cambiar estados y notas'],
            ['module' => 'orders', 'slug' => 'orders.tracking', 'label' => 'Agregar tracking info'],
            ['module' => 'orders', 'slug' => 'orders.refund', 'label' => 'Gestionar reembolsos (Dinero)'],
            
            // Customers
            ['module' => 'customers', 'slug' => 'customers.view', 'label' => 'Ver datos de clientes'],
            ['module' => 'customers', 'slug' => 'customers.manage', 'label' => 'Editar datos de clientes'],
            
            // Marketing
            ['module' => 'marketing', 'slug' => 'coupons.manage', 'label' => 'Gestionar Cupones'],
            ['module' => 'marketing', 'slug' => 'campaigns.manage', 'label' => 'Gestionar Campañas'],
            
            // System
            ['module' => 'system', 'slug' => 'reports.view', 'label' => 'Ver reportes financieros'],
            ['module' => 'system', 'slug' => 'settings.manage', 'label' => 'Gestionar configuración'],
            ['module' => 'system', 'slug' => 'users.manage', 'label' => 'Gestionar usuarios y permisos'],
        ];

        $id = 1;
        foreach ($capabilities as $cap) {
            $this->capabilities[$id] = new PlazaCapability(
                id: $id++,
                module: $cap['module'],
                slug: $cap['slug'],
                label: $cap['label']
            );
        }
    }

    public function findById(int $id): ?PlazaCapability
    {
        return $this->capabilities[$id] ?? null;
    }

    public function findBySlug(string $slug): ?PlazaCapability
    {
        foreach ($this->capabilities as $capability) {
            if ($capability->slug === $slug) {
                return $capability;
            }
        }
        return null;
    }

    public function findByModule(string $module): array
    {
        return array_filter(
            $this->capabilities,
            fn($cap) => $cap->module === $module
        );
    }

    public function all(): array
    {
        return array_values($this->capabilities);
    }
}

