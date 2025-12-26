<?php

namespace App\Data;

class PlazaAuthAudit
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $userId = null,
        public readonly string $eventType, // login_success, login_failed, password_reset, role_change
        public readonly string $ipAddress,
        public readonly ?string $userAgent = null,
        public readonly ?array $metadata = null,
        public readonly ?\DateTime $createdAt = null
    ) {}
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt ?? new \DateTime();
    }
}

