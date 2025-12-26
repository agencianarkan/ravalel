<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaStore;

interface PlazaStoreRepositoryInterface
{
    public function findById(int $id): ?PlazaStore;
    public function findByDomain(string $domain): ?PlazaStore;
    public function findByOwnerId(int $ownerId): array;
    public function create(array $data): PlazaStore;
    public function update(int $id, array $data): ?PlazaStore;
    public function delete(int $id): bool;
    public function all(): array;
}

