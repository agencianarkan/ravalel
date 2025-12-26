<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaCustomOverride;

interface PlazaCustomOverrideRepositoryInterface
{
    public function findById(int $id): ?PlazaCustomOverride;
    public function findByMembershipId(int $membershipId): array;
    public function findByMembershipAndCapability(int $membershipId, int $capabilityId): ?PlazaCustomOverride;
    public function create(array $data): PlazaCustomOverride;
    public function update(int $id, array $data): ?PlazaCustomOverride;
    public function delete(int $id): bool;
    public function deleteByMembership(int $membershipId): bool;
}

