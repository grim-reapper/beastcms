<?php

namespace Modules\ThemeGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Traits\LoadAndPublishDataTrait;

class ThemeGeneratorServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->app->register(CommandServiceProvider::class);
    }
}
