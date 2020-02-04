<?php

namespace Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Base\Foundation\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Setting\Eloquent\SettingRepository;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Facades\SettingFacade;
use Modules\Setting\Repositories\Caches\SettingCacheDecorator;
use Modules\Setting\Repositories\Interfaces\SettingInterface;
use Modules\Setting\Support\SettingsManager;
use Modules\Setting\Support\SettingStore;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
class SettingServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    protected $defer = true;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->publishAssets();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->setNamespace('Setting')
            ->loadAndPublishConfigurations(['general']);

        $this->app->singleton(SettingsManager::class, function (Application $app) {
            return new SettingsManager($app);
        });

        $this->app->bind(SettingStore::class, function (Application $app) {
            return $app->make(SettingsManager::class)->driver();
        });

        AliasLoader::getInstance()->alias('Setting', SettingFacade::class);

        $this->app->bind(SettingInterface::class, function () {
            return new SettingCacheDecorator(
                new SettingRepository(new Setting)
            );
        });

        Helper::autoload(__DIR__ . '/../helpers');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            SettingsManager::class,
            SettingStore::class,
            'setting',
        ];
    }
}
