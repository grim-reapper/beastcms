<?php

namespace Modules\Base\Providers;

use Modules\Base\Commands\ClearLogCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ClearLogCommand::class,
        ]);
    }
}
