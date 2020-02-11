<?php

namespace Modules\DevTool\Providers;

use Modules\DevTool\Commands\InstallCommand;
use Modules\DevTool\Commands\LocaleCreateCommand;
use Modules\DevTool\Commands\LocaleRemoveCommand;
use Modules\DevTool\Commands\Make\ControllerMakeCommand;
use Modules\DevTool\Commands\Make\FormMakeCommand;
use Modules\DevTool\Commands\Make\ModelMakeCommand;
use Modules\DevTool\Commands\Make\RepositoryMakeCommand;
use Modules\DevTool\Commands\Make\RequestMakeCommand;
use Modules\DevTool\Commands\Make\RouteMakeCommand;
use Modules\DevTool\Commands\Make\TableMakeCommand;
use Modules\DevTool\Commands\PackageCreateCommand;
use Modules\DevTool\Commands\RebuildPermissionsCommand;
use Modules\DevTool\Commands\TestSendMailCommand;
use Modules\DevTool\Commands\TruncateTablesCommand;
use Modules\DevTool\Commands\UserCreateCommand;
use Modules\DevTool\Commands\PackageMakeCrudCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TableMakeCommand::class,
                ControllerMakeCommand::class,
                RouteMakeCommand::class,
                RequestMakeCommand::class,
                FormMakeCommand::class,
                ModelMakeCommand::class,
                RepositoryMakeCommand::class,
                PackageCreateCommand::class,
                PackageMakeCrudCommand::class,
                InstallCommand::class,
                TestSendMailCommand::class,
                TruncateTablesCommand::class,
                UserCreateCommand::class,
                RebuildPermissionsCommand::class,
                LocaleRemoveCommand::class,
                LocaleCreateCommand::class,
            ]);
        }
    }
}
