<?php

namespace App\Data;

class PlazaUser
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly ?string $fullName = null,
        public readonly string $status = 'pending', // pending, active, suspended
        public readonly ?string $verificationToken = null,
        public readonly ?string $resetToken = null,
        public readonly ?\DateTime $tokenExpiresAt = null,
        public readonly int $failedLoginAttempts = 0,
        public readonly ?\DateTime $lockoutUntil = null,
        public readonly ?\DateTime $lastLoginAt = null,
        public readonly ?\DateTime $createdAt = null
    ) {}
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt ?? new \DateTime();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isLocked(): bool
    {
        return $this->lockoutUntil !== null && $this->lockoutUntil > new \DateTime();
    }

    public function canAttemptLogin(): bool
    {
        return $this->isActive() && !$this->isLocked();
    }
}

