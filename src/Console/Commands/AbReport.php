<?php

namespace folez\LaravelAB\Console\Commands;

use folez\LaravelAB\Models\AbVariant;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class AbReport extends Command
{
    protected $signature = 'ab:report';

    protected $description = 'Shows a table with variant and goal insights';

    public function handle(): void
    {
        $header = $this->prepareHeader();
        $body = $this->prepareBody();

        $this->table($header, $body);
    }

    /** @return array<string,mixed> */
    public function prepareHeader(): array
    {
        $header = [
            'Variant',
            'Visitors',
        ];

        return array_merge($header, array_map(function ($item) {
            return 'Goal ' . $item;
        }, config('laravel-ab.goals')));
    }

    public function prepareBody(): Collection
    {
        return AbVariant::all()->map(function ($item) {
            $return = [$item->name, $item->visitors];

            $goalConversations = $item->goals->sortBy('id')->pluck('hit')->map(function ($hit) use ($item) {
                $item->visitors = $item->visitors ?: 1; // prevent division by zero exception

                return $hit . ' (' . number_format($hit / $item->visitors * 100) . '%)';
            });

            return array_merge($return, $goalConversations->toArray());
        });
    }
}
