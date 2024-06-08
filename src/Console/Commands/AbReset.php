<?php

namespace FoLez\LaravelAB\Console\Commands;

use FoLez\LaravelAB\Models\AbGoal;
use FoLez\LaravelAB\Models\AbVariant;
use FoLez\LaravelAB\Models\AbVisitor;
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
