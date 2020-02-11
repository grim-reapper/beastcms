<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Events\RouteMatched;
use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Dashboard\Entities\DashboardWidget;
use Modules\Dashboard\Entities\DashboardWidgetSetting;
use Modules\Dashboard\Repositories\Caches\DashboardWidgetCacheDecorator;
use Modules\Dashboard\Repositories\Caches\DashboardWidgetSettingCacheDecorator;
use Modules\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use Modules\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use Modules\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Modules\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setNamespace('Dashboard')
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadMigrations();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                   'id'          => 'cms-core-dashboard',
                   'priority'    => 0,
                   'parent_id'   => null,
                   'name'        => 'Base::layouts.dashboard',
                   'icon'        => 'fa fa-home',
                   'url'         => route('dashboard.index'),
                   'permissions' => [],
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
        $this->app->bind(DashboardWidgetInterface::class, function () {
            return new DashboardWidgetCacheDecorator(
                new DashboardWidgetRepository(new DashboardWidget)
            );
        });

        $this->app->bind(DashboardWidgetSettingInterface::class, function () {
            return new DashboardWidgetSettingCacheDecorator(
                new DashboardWidgetSettingRepository(new DashboardWidgetSetting)
            );
        });

        Helper::autoload(__DIR__ . '/../Helpers');
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
