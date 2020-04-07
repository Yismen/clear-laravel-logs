<?php

namespace Dainsys\Commands;

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
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/clearlogs.php', 'clearlogs'
        );
    }
}