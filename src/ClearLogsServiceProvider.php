<?php

namespace Dainsys\ClearLogs;

use Dainsys\ClearLogs\Commands\ClearLogsCommand;
use Illuminate\Support\ServiceProvider;

class ClearLogsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearLogsCommand::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/dainsys_clearlogs.php' => config_path('dainsys_clearlogs.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/dainsys_clearlogs.php',
            'dainsys_clearlogs'
        );
    }
}
