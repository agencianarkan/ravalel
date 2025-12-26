<?php

namespace App\Repositories\Mock;

use App\Data\PlazaStore;
use App\Repositories\Contracts\PlazaStoreRepositoryInterface;

class MockPlazaStoreRepository implements PlazaStoreRepositoryInterface
{
    private array $stores = [];
    private int $nextId = 1;

    public function __construct()
    {
        $this->initializeMockData();
    }

    private function initializeMockData(): void
    {
        // Tienda 1
        $this->stores[1] = new PlazaStore(
            id: 1,
            name: 'Zapatillas Chile',
            domainUrl: 'https://zapatillas-chile.cl',
            apiKey: 'wc_api_key_123456',
            ownerId: 1,
            logoUrl: 'https://example.com/logos/zapatillas.png',
            createdAt: new \DateTime('2024-01-01 10:00:00')
        );

        // Tienda 2
        $this->stores[2] = new PlazaStore(
            id: 2,
            name: 'Ropa Deportiva MX',
            domainUrl: 'https://ropadeportiva.mx',
            apiKey: 'wc_api_key_789012',
            ownerId: 1,
            logoUrl: null,
            createdAt: new \DateTime('2024-01-15 10:00:00')
        );

        // Tienda 3
        $this->stores[3] = new PlazaStore(
            id: 3,
            name: 'Accesorios AR',
            domainUrl: 'https://accesorios-ar.com',
            apiKey: 'wc_api_key_345678',
            ownerId: 2,
            logoUrl: 'https://example.com/logos/accesorios.png',
            createdAt: new \DateTime('2024-02-01 10:00:00')
        );

        $this->nextId = 4;
    }

    public function findById(int $id): ?PlazaStore
    {
        return $this->stores[$id] ?? null;
    }

    public function findByDomain(string $domain): ?PlazaStore
    {
        foreach ($this->stores as $store) {
            if (str_contains($store->domainUrl, $domain)) {
                return $store;
            }
        }
        return null;
    }

    public function findByOwnerId(int $ownerId): array
    {
        return array_filter($this->stores, fn($store) => $store->ownerId === $ownerId);
    }

    public function create(array $data): PlazaStore
    {
        $store = new PlazaStore(
            id: $this->nextId++,
            name: $data['name'],
            domainUrl: $data['domain_url'],
            apiKey: $data['api_key'] ?? null,
            ownerId: $data['owner_id'] ?? null,
            logoUrl: $data['logo_url'] ?? null,
            createdAt: new \DateTime()
        );

        $this->stores[$store->id] = $store;
        return $store;
    }

    public function update(int $id, array $data): ?PlazaStore
    {
        if (!isset($this->stores[$id])) {
            return null;
        }

        $store = $this->stores[$id];
        $updatedStore = new PlazaStore(
            id: $store->id,
            name: $data['name'] ?? $store->name,
            domainUrl: $data['domain_url'] ?? $store->domainUrl,
            apiKey: $data['api_key'] ?? $store->apiKey,
            ownerId: $data['owner_id'] ?? $store->ownerId,
            logoUrl: $data['logo_url'] ?? $store->logoUrl,
            createdAt: $store->createdAt
        );

        $this->stores[$id] = $updatedStore;
        return $updatedStore;
    }

    public function delete(int $id): bool
    {
        if (isset($this->stores[$id])) {
            unset($this->stores[$id]);
            return true;
        }
        return false;
    }

    public function all(): array
    {
        return array_values($this->stores);
    }
}

