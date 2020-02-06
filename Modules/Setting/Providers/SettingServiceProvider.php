<?php

namespace Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Setting\Eloquent\SettingRepository;
use Modules\Setting\Entities\Setting;
use Modules\Setting\Facades\SettingFacade;
use Modules\Setting\Repositories\Caches\SettingCacheDecorator;
use Modules\Setting\Repositories\Interfaces\SettingInterface;
use Modules\Setting\Supports\SettingsManager;
use Modules\Setting\Supports\SettingStore;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Event;
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
        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                   'id'          => 'cms-core-settings',
                   'priority'    => 998,
                   'parent_id'   => null,
                   'name'        => 'core/setting::setting.title',
                   'icon'        => 'fa fa-cogs',
                   'url'         => route('settings.options'),
                   'permissions' => ['settings.options'],
               ])
                ->registerItem([
                   'id'          => 'cms-core-settings-general',
                   'priority'    => 1,
                   'parent_id'   => 'cms-core-settings',
                   'name'        => 'core/base::layouts.setting_general',
                   'icon'        => null,
                   'url'         => route('settings.options'),
                   'permissions' => ['settings.options'],
               ])
                ->registerItem([
                   'id'          => 'cms-core-settings-email',
                   'priority'    => 2,
                   'parent_id'   => 'cms-core-settings',
                   'name'        => 'core/base::layouts.setting_email',
                   'icon'        => null,
                   'url'         => route('settings.email'),
                   'permissions' => ['settings.email'],
               ])
                ->registerItem([
                   'id'          => 'cms-core-settings-media',
                   'priority'    => 3,
                   'parent_id'   => 'cms-core-settings',
                   'name'        => 'core/setting::setting.media.title',
                   'icon'        => null,
                   'url'         => route('settings.media'),
                   'permissions' => ['settings.media'],
               ]);
        });
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
