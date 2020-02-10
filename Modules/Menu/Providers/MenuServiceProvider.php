<?php

namespace Modules\Menu\Providers;

use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Menu\Entities\Menu as MenuModel;
use Modules\Menu\Entities\MenuLocation;
use Modules\Menu\Entities\MenuNode;
use Modules\Menu\Repositories\Caches\MenuCacheDecorator;
use Modules\Menu\Repositories\Caches\MenuLocationCacheDecorator;
use Modules\Menu\Repositories\Caches\MenuNodeCacheDecorator;
use Modules\Menu\Repositories\Eloquent\MenuLocationRepository;
use Modules\Menu\Repositories\Eloquent\MenuNodeRepository;
use Modules\Menu\Repositories\Eloquent\MenuRepository;
use Modules\Menu\Repositories\Interfaces\MenuInterface;
use Modules\Menu\Repositories\Interfaces\MenuLocationInterface;
use Modules\Menu\Repositories\Interfaces\MenuNodeInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MenuInterface::class, function () {
            return new MenuCacheDecorator(
                new MenuRepository(new MenuModel)
            );
        });

        $this->app->bind(MenuNodeInterface::class, function () {
            return new MenuNodeCacheDecorator(
                new MenuNodeRepository(new MenuNode)
            );
        });

        $this->app->bind(MenuLocationInterface::class, function () {
            return new MenuLocationCacheDecorator(
                new MenuLocationRepository(new MenuLocation)
            );
        });

        $this->setNamespace('Menu')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                                   'id'          => 'cms-core-menu',
                                   'priority'    => 2,
                                   'parent_id'   => 'cms-core-appearance',
                                   'name'        => 'Base::layouts.menu',
                                   'icon'        => null,
                                   'url'         => route('menus.index'),
                                   'permissions' => ['menus.index'],
                               ]);

            if (!defined('THEME_MODULE_SCREEN_NAME')) {
                dashboard_menu()
                    ->registerItem([
                                       'id'          => 'cms-core-appearance',
                                       'priority'    => 996,
                                       'parent_id'   => null,
                                       'name'        => 'Base::layouts.appearance',
                                       'icon'        => 'fa fa-paint-brush',
                                       'url'         => '#',
                                       'permissions' => [],
                                   ]);
            }

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink('Menu', route('menus.index'), 'appearance');
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Helper::autoload(__DIR__ . '/../Helpers');
        $this->app->register(RouteServiceProvider::class);
    }
}
