<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaRole;

interface PlazaRoleRepositoryInterface
{
    public function findById(int $id): ?PlazaRole;
    public function findBySlug(string $slug): ?PlazaRole;
    public function all(): array;
}

