<?php

namespace folez\LaravelAB\Tests;

use folez\LaravelAB\Facades\AbTestingFacade;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected array $variants = [
        'firstVariant',
        'secondVariant',
    ];
    protected array $goals = [
        'firstGoal',
        'secondGoal',
    ];

    protected function setUp(): void
    {
        Config::set([
            'database' => [
                'default' => 'ab',
                'connections' => [
                    'ab' => [
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                        'prefix' => '',
                    ],
                ],
            ],
            'laravel-ab' => [
                'variants' => $this->variants,
                'goals' => $this->goals,
            ],
        ]);

        parent::setUp();

        $this->artisan('db:seed');
    }

    protected function newVisitor(): void
    {
        AbTestingFacade::resetVisitor();
        AbTestingFacade::pageView();
    }
}
