<?php

namespace Modules\PluginManagement\Providers;

use Modules\PluginManagement\Commands\PluginActivateCommand;
use Modules\PluginManagement\Commands\PluginAssetsPublishCommand;
use Modules\PluginManagement\Commands\PluginDeactivateCommand;
use Modules\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
        ]);
    }
}
