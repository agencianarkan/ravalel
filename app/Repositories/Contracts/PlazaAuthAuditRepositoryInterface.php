<?php

namespace App\Repositories\Contracts;

use App\Data\PlazaAuthAudit;

interface PlazaAuthAuditRepositoryInterface
{
    public function create(array $data): PlazaAuthAudit;
    public function findByUserId(int $userId, ?int $limit = null): array;
    public function findByEventType(string $eventType, ?int $limit = null): array;
    public function findByIpAddress(string $ipAddress, ?int $limit = null): array;
}

