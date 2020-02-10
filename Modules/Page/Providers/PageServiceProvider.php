<?php

namespace Modules\Page\Providers;

use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Page\Entities\Page;
use Modules\Page\Repositories\Caches\PageCacheDecorator;
use Modules\Page\Repositories\Eloquent\PageRepository;
use Modules\Page\Repositories\Interfaces\PageInterface;
use Modules\Shortcode\View\View;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(PageInterface::class, function () {
            return new PageCacheDecorator(new PageRepository(new Page));
        });

        $this->setNamespace('Page')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations();

        $this->app->register(HookServiceProvider::class);
        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                                               'id'          => 'cms-core-page',
                                               'priority'    => 2,
                                               'parent_id'   => null,
                                               'name'        => 'Page::pages.menu_name',
                                               'icon'        => 'fa fa-book',
                                               'url'         => route('pages.index'),
                                               'permissions' => ['pages.index'],
                                           ]);

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink('Page', route('pages.index'), 'add-new');
            }
        });

        if (function_exists('shortcode')) {
            view()->composer(['Page::themes.page'], function (View $view) {
                $view->withShortcodes();
            });
        }
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
