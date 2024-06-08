<?php

namespace folez\LaravelAb;

use folez\LaravelAB\Contracts\VisitorInterface;
use folez\LaravelAB\Models\AbVariant;

class SessionVisitor implements VisitorInterface
{
    private const SESSION_KEY_VARIANT = 'ab_testing_variant';

    public function hasVariant(): bool
    {
        return (bool) session(self::SESSION_KEY_VARIANT);
    }

    public function getVariant(): AbVariant|string
    {
        return session(self::SESSION_KEY_VARIANT);
    }

    public function setVariant(AbVariant $variant): void
    {
        session([self::SESSION_KEY_VARIANT => $variant]);
    }
}
