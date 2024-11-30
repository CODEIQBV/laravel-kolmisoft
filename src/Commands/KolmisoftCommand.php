<?php

namespace CODEIQBV\Kolmisoft\Commands;

use Illuminate\Console\Command;

class KolmisoftCommand extends Command
{
    public $signature = 'laravel-kolmisoft';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
