<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaMembership;

interface PlazaMembershipRepositoryInterface
{
    public function findById(int $id): ?PlazaMembership;
    public function findByUserAndStore(int $userId, int $storeId): ?PlazaMembership;
    public function findByUserId(int $userId): array;
    public function findByStoreId(int $storeId): array;
    public function create(array $data): PlazaMembership;
    public function update(int $id, array $data): ?PlazaMembership;
    public function delete(int $id): bool;
}

