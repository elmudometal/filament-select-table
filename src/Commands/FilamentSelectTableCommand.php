<?php

namespace ElmudoDev\FilamentSelectTable\Commands;

use Illuminate\Console\Command;

class FilamentSelectTableCommand extends Command
{
    public $signature = 'filament-select-table';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
