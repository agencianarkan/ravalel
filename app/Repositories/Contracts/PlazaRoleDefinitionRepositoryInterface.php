<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaRoleDefinition;

interface PlazaRoleDefinitionRepositoryInterface
{
    public function findByRoleId(int $roleId): array;
    public function findByCapabilityId(int $capabilityId): array;
    public function findByRoleAndCapability(int $roleId, int $capabilityId): ?PlazaRoleDefinition;
    public function all(): array;
}

