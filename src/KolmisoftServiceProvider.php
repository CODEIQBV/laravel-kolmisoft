<?php

namespace CODEIQBV\Kolmisoft;

use Illuminate\Support\ServiceProvider;

class KolmisoftServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/kolmisoft.php', 'kolmisoft');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/kolmisoft.php' => config_path('kolmisoft.php'),
        ], 'kolmisoft-config');
    }
}
