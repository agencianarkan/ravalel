<?php

namespace App\Services;

use App\Repositories\Contracts\PlazaAuthAuditRepositoryInterface;

class PlazaAuditService
{
    public function __construct(
        private PlazaAuthAuditRepositoryInterface $auditRepository
    ) {}

    /**
     * Registrar evento de auditoría
     */
    public function log(string $eventType, ?int $userId = null, ?array $metadata = null): void
    {
        $this->auditRepository->create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => request()->ip() ?? '0.0.0.0',
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata
        ]);
    }

    /**
     * Obtener historial de auditoría de un usuario
     */
    public function getUserHistory(int $userId, ?int $limit = 50): array
    {
        return $this->auditRepository->findByUserId($userId, $limit);
    }

    /**
     * Obtener eventos por tipo
     */
    public function getEventsByType(string $eventType, ?int $limit = 100): array
    {
        return $this->auditRepository->findByEventType($eventType, $limit);
    }

    /**
     * Obtener eventos por IP
     */
    public function getEventsByIp(string $ipAddress, ?int $limit = 100): array
    {
        return $this->auditRepository->findByIpAddress($ipAddress, $limit);
    }
}

