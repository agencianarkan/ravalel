<?php

namespace App\Data;

class PlazaRole
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug, // owner, shop_manager, logistics, editor
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly bool $isCustomizable = true
    ) {}
}

