<?php

namespace App\Data;

class PlazaCapability
{
    public function __construct(
        public readonly int $id,
        public readonly string $module, // inventory, orders, customers, marketing, system
        public readonly string $slug, // orders.view, orders.manage, etc.
        public readonly string $label
    ) {}
}

