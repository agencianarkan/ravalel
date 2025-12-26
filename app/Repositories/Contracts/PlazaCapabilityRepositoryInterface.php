<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaCapability;

interface PlazaCapabilityRepositoryInterface
{
    public function findById(int $id): ?PlazaCapability;
    public function findBySlug(string $slug): ?PlazaCapability;
    public function findByModule(string $module): array;
    public function all(): array;
}

