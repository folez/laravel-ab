<?php

namespace folez\LaravelAB\Tests\Integration;

use folez\LaravelAB\Console\Commands\AbReport;
use folez\LaravelAB\Facades\AbTestingFacade;
use folez\LaravelAB\Tests\TestCase;

class AbReportTest extends TestCase
{
    public function testHandle(): void
    {
        $reportCommand = new AbReport();

        $this->assertEquals([
            'Variant',
            'Visitors',
            'Goal firstGoal',
            'Goal secondGoal',
        ], $reportCommand->prepareHeader());

        $this->assertEquals([], $reportCommand->prepareBody()->toArray());

        AbTestingFacade::pageView();

        $expected = [
            [
                'firstVariant',
                1,
                '0 (0%)',
                '0 (0%)',
            ],
            [
                'secondVariant',
                0,
                '0 (0%)',
                '0 (0%)',
            ],
        ];
        $this->assertEquals($expected, $reportCommand->prepareBody()->toArray());

        $this->newVisitor();

        $expected = [
            [
                'firstVariant',
                1,
                '0 (0%)',
                '0 (0%)',
            ],
            [
                'secondVariant',
                1,
                '0 (0%)',
                '0 (0%)',
            ],
        ];
        $this->assertEquals($expected, $reportCommand->prepareBody()->toArray());

        AbTestingFacade::completeGoal('firstGoal');

        $expected = [
            [
                'firstVariant',
                1,
                '0 (0%)',
                '0 (0%)',
            ],
            [
                'secondVariant',
                1,
                '1 (100%)',
                '0 (0%)',
            ],
        ];
        $this->assertEquals($expected, $reportCommand->prepareBody()->toArray());

        $this->newVisitor();
        $this->newVisitor();
        $this->newVisitor();

        $expected = [
            [
                'firstVariant',
                2,
                '0 (0%)',
                '0 (0%)',
            ],
            [
                'secondVariant',
                3,
                '1 (33%)',
                '0 (0%)',
            ],
        ];
        $this->assertEquals($expected, $reportCommand->prepareBody()->toArray());
    }
}
