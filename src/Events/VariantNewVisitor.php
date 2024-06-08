<?php

namespace folez\LaravelAB\Events;

use folez\LaravelAB\Contracts\VisitorInterface;
use folez\LaravelAB\Models\AbVariant;

class VariantNewVisitor
{
    public function __construct(public AbVariant $variant, public VisitorInterface $visitor)
    {
    }
}
