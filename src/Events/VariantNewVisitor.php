<?php

namespace FoLez\LaravelAB\Events;

use FoLez\LaravelAB\Contracts\VisitorInterface;
use FoLez\LaravelAB\Models\AbVariant;

class VariantNewVisitor
{
    public function __construct(public AbVariant $variant, public VisitorInterface $visitor)
    {
    }
}
