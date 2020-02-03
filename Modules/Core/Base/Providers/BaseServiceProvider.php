<?php

namespace Modules\Core\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Base\Foundation\CustomResourceRegistrar;
use Modules\Core\Base\Foundation\Helper;
use Modules\Core\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Router;
class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar(Router::class));
        });
        Helper::autoload(__DIR__ . '/../helpers');

        $this->setNamespace('core/base')
            ->loadAndPublishConfigurations(['general']);

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
