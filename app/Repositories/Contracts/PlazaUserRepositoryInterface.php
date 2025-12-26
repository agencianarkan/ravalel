<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaUser;

interface PlazaUserRepositoryInterface
{
    public function findById(int $id): ?PlazaUser;
    public function findByEmail(string $email): ?PlazaUser;
    public function create(array $data): PlazaUser;
    public function update(int $id, array $data): ?PlazaUser;
    public function delete(int $id): bool;
    public function incrementFailedAttempts(int $userId): void;
    public function resetFailedAttempts(int $userId): void;
    public function setLockout(int $userId, \DateTime $until): void;
    public function clearLockout(int $userId): void;
    public function updateLastLogin(int $userId): void;
}

