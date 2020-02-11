<?php

namespace Modules\ThemeGenerator\Providers;

use Modules\ThemeGenerator\Commands\ThemeCreateCommand;
use Modules\ThemeGenerator\Commands\ThemeInstallSampleDataCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ThemeCreateCommand::class,
            ]);
        }

        $this->commands([
            ThemeInstallSampleDataCommand::class,
        ]);
    }
}
