<?php

namespace Modules\Widget\Providers;

use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Widget\Factories\WidgetFactory;
use Modules\Widget\Misc\LaravelApplicationWrapper;
use Modules\Widget\Entities\Widget;
use Modules\Widget\Repositories\Caches\WidgetCacheDecorator;
use Modules\Widget\Repositories\Eloquent\WidgetRepository;
use Modules\Widget\Repositories\Interfaces\WidgetInterface;
use Modules\Widget\WidgetGroupCollection;
use Modules\Widget\Widgets\Text;
use Event;
use File;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use WidgetGroup;

class WidgetServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WidgetInterface::class, function () {
            return new WidgetCacheDecorator(new WidgetRepository(new Widget));
        });

        $this->app->bind('Modules.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper);
        });

        $this->app->singleton('Modules.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper);
        });

        Helper::autoload(__DIR__ . '/../Helpers');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setNamespace('Widget')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->booted(function () {

            WidgetGroup::setGroup([
                'id'          => 'primary_sidebar',
                'name'        => 'Primary sidebar',
                'description' => 'This is primary sidebar section',
            ]);

            register_widget(Text::class);

            $widget_path = theme_path(setting('theme') . '/widgets');
            $widgets = scan_folder($widget_path);
            if (!empty($widgets) && is_array($widgets)) {
                foreach ($widgets as $widget) {
                    $registration = $widget_path . '/' . $widget . '/registration.php';
                    if (File::exists($registration)) {
                        File::requireOnce($registration);
                    }
                }
            }
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-widget',
                    'priority'    => 3,
                    'parent_id'   => 'cms-core-appearance',
                    'name'        => 'Base::layouts.widgets',
                    'icon'        => null,
                    'url'         => route('widgets.index'),
                    'permissions' => ['widgets.index'],
                ]);

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink('Widget', route('widgets.index'), 'appearance');
            }
        });
    }
}
