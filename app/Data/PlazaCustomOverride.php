<?php

namespace App\Data;

class PlazaCustomOverride
{
    public function __construct(
        public readonly int $id,
        public readonly int $membershipId,
        public readonly int $capabilityId,
        public readonly bool $isGranted // true = granted, false = denied
    ) {}
}

