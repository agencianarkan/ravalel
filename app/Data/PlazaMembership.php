<?php

namespace App\Data;

class PlazaMembership
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly int $storeId,
        public readonly int $roleId,
        public readonly bool $isCustomMode = false,
        public readonly ?int $invitedBy = null,
        public readonly \DateTime $createdAt = new \DateTime()
    ) {}
}

