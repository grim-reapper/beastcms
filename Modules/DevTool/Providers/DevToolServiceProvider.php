<?php

namespace Modules\DevTool\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Traits\LoadAndPublishDataTrait;

class DevToolServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('DevTool');

        $this->app->register(CommandServiceProvider::class);
    }
}
