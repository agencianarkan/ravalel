<?php

namespace App\Data;

class PlazaStore
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $domainUrl,
        public readonly ?string $apiKey = null,
        public readonly ?int $ownerId = null,
        public readonly ?string $logoUrl = null,
        public readonly ?\DateTime $createdAt = null
    ) {}
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt ?? new \DateTime();
    }
}

