<?php

namespace App\Repositories\Mock;

use App\Data\PlazaAuthAudit;
use App\Repositories\Contracts\PlazaAuthAuditRepositoryInterface;

class MockPlazaAuthAuditRepository implements PlazaAuthAuditRepositoryInterface
{
    private array $audits = [];
    private int $nextId = 1;

    public function create(array $data): PlazaAuthAudit
    {
        $audit = new PlazaAuthAudit(
            id: $this->nextId++,
            userId: $data['user_id'] ?? null,
            eventType: $data['event_type'],
            ipAddress: $data['ip_address'],
            userAgent: $data['user_agent'] ?? null,
            metadata: $data['metadata'] ?? null,
            createdAt: new \DateTime()
        );

        $this->audits[$audit->id] = $audit;
        return $audit;
    }

    public function findByUserId(int $userId, ?int $limit = null): array
    {
        $results = array_filter(
            $this->audits,
            fn($audit) => $audit->userId === $userId
        );

        // Ordenar por fecha descendente
        usort($results, fn($a, $b) => $b->createdAt <=> $a->createdAt);

        if ($limit !== null) {
            return array_slice($results, 0, $limit);
        }

        return $results;
    }

    public function findByEventType(string $eventType, ?int $limit = null): array
    {
        $results = array_filter(
            $this->audits,
            fn($audit) => $audit->eventType === $eventType
        );

        usort($results, fn($a, $b) => $b->createdAt <=> $a->createdAt);

        if ($limit !== null) {
            return array_slice($results, 0, $limit);
        }

        return $results;
    }

    public function findByIpAddress(string $ipAddress, ?int $limit = null): array
    {
        $results = array_filter(
            $this->audits,
            fn($audit) => $audit->ipAddress === $ipAddress
        );

        usort($results, fn($a, $b) => $b->createdAt <=> $a->createdAt);

        if ($limit !== null) {
            return array_slice($results, 0, $limit);
        }

        return $results;
    }
}

