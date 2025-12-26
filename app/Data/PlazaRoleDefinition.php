<?php

namespace App\Data;

class PlazaRoleDefinition
{
    public function __construct(
        public readonly int $id,
        public readonly int $roleId,
        public readonly int $capabilityId
    ) {}
}

