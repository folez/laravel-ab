<?php

namespace folez\LaravelAB\Console\Commands;

use folez\LaravelAB\Models\AbGoal;
use folez\LaravelAB\Models\AbVariant;
use folez\LaravelAB\Models\AbVisitor;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class AbReset extends Command
{
    use ConfirmableTrait;

    protected $signature = 'ab:reset';

    protected $description = 'Deletes all variants visitors and goal completions';

    public function handle(): void
    {
        if (! $this->confirmToProceed('Deletes all variants visitors and goal completions?', fn() => true)) {
            return;
        }

        AbGoal::query()->truncate();
        AbVariant::query()->truncate();
        AbVisitor::query()->truncate();

        $this->info('Successfully deleted all variants visitors and goal completions.');
    }
}
