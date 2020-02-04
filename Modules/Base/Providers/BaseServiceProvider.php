<?php

namespace Modules\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Foundation\CustomResourceRegistrar;
use Modules\Base\Foundation\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\ResourceRegistrar;
use Modules\Setting\Providers\SettingServiceProvider;
use Modules\Setting\Support\SettingStore;

class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar($app['router']);
        });
        $this->app->register(RouteServiceProvider::class);
        Helper::autoload(__DIR__ . '/../helpers');
        $this->setNamespace('Base')->loadAndPublishConfigurations(['config']);

        $this->app->register(SettingServiceProvider::class);

        $config = $this->app->make('config');
        $setting = $this->app->make(SettingStore::class);

    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
