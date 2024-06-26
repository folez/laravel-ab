<?php

namespace folez\LaravelAB\Contracts;

use folez\LaravelAB\Models\AbVariant;

interface VisitorInterface
{
    public function hasVariant(): bool;

    public function getVariant(): AbVariant|string;

    public function setVariant(AbVariant $variant): void;
}
