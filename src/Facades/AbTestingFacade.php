<?php

namespace FoLez\LaravelAB\Facades;

use Illuminate\Support\Facades\Facade;

class AbTestingFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ab-testing';
    }
}
