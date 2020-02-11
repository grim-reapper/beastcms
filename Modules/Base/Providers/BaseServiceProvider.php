<?php

namespace Modules\Base\Providers;

use Modules\Base\Exceptions\Handler;
use Modules\Base\Http\Middleware\DisableInDemoModeMiddleware;
use Modules\Base\Http\Middleware\HttpsProtocolMiddleware;
use Modules\Base\Http\Middleware\LocaleMiddleware;
use Modules\Base\Entities\MetaBox as MetaBoxModel;
use Modules\Base\Repositories\Caches\MetaBoxCacheDecorator;
use Modules\Base\Repositories\Eloquent\MetaBoxRepository;
use Modules\Base\Repositories\Interfaces\MetaBoxInterface;
use Modules\Base\Supports\CustomResourceRegistrar;
use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Setting\Providers\SettingServiceProvider;
use Modules\Setting\Supports\SettingStore;
use Event;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MetaBox;

class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * Register any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar($app['router']);
        });
        Helper::autoload(__DIR__ . '/../Helpers');

        $this->setNamespace('Base')
            ->loadAndPublishConfigurations(['general']);

        $this->app->register(SettingServiceProvider::class);

        $config = $this->app->make('config');
        $setting = $this->app->make(SettingStore::class);

        $config->set([
            'app.timezone'                     => $setting->get('time_zone', $config->get('app.timezone')),
            'jsvalidation.view'                => 'jsvalidation::bootstrap4',
            'ziggy.blacklist'                  => ['debugbar.*'],
            'session.cookie'                   => 'Modules_session',
            'filesystems.default'              => $setting->get('media_driver', $config->get('filesystems.default')),
            'filesystems.disks.s3.key'         => $setting
                ->get('media_aws_access_key_id', $config->get('filesystems.disks.s3.key')),
            'filesystems.disks.s3.secret'      => $setting
                ->get('media_aws_secret_key', $config->get('filesystems.disks.s3.secret')),
            'filesystems.disks.s3.region'      => $setting
                ->get('media_aws_default_region', $config->get('filesystems.disks.s3.region')),
            'filesystems.disks.s3.bucket'      => $setting
                ->get('media_aws_bucket', $config->get('filesystems.disks.s3.bucket')),
            'filesystems.disks.s3.url'         => $setting
                ->get('media_aws_url', $config->get('filesystems.disks.s3.url')),
            'app.debug_blacklist'              => [
                '_ENV'    => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_SERVER' => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_POST'   => [
                    'password',
                ],
            ],
            'datatables-buttons.pdf_generator' => 'excel',
        ]);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', LocaleMiddleware::class);
        $router->pushMiddlewareToGroup('web', HttpsProtocolMiddleware::class);
        $router->aliasMiddleware('preventDemo', DisableInDemoModeMiddleware::class);

        $this->app->bind(MetaBoxInterface::class, function () {
            return new MetaBoxCacheDecorator(new MetaBoxRepository(new MetaBoxModel));
        });
    }

    public function boot()
    {
        $this->setNamespace('Base')
            ->loadAndPublishConfigurations(['permissions', 'assets'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        $this->app->booted(function () {
            do_action(BASE_ACTION_INIT);
            add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 8, 2);

            $config = $this->app->make('config');
            $config->set([
                'app.locale' => $config->get('Base.general.locale', $config->get('app.locale')),
                'purifier.settings.default.AutoFormat.AutoParagraph' => false,
                'purifier.settings.default.AutoFormat.RemoveEmpty' => false,
            ]);
        });

        Event::listen(RouteMatched::class, function () {
            $this->registerDefaultMenus();
        });
    }

    /**
     * Add default dashboard menu for core
     */
    public function registerDefaultMenus()
    {
        dashboard_menu()
            ->registerItem([
                'id'          => 'cms-core-platform-administration',
                'priority'    => 999,
                'parent_id'   => null,
                'name'        => 'Base::layouts.platform_admin',
                'icon'        => 'fa fa-user-shield',
                'url'         => null,
                'permissions' => ['users.index'],
            ])
            ->registerItem([
                'id'          => 'cms-core-system-information',
                'priority'    => 5,
                'parent_id'   => 'cms-core-platform-administration',
                'name'        => 'Base::system.info.title',
                'icon'        => null,
                'url'         => route('system.info'),
                'permissions' => [ACL_ROLE_SUPER_USER],
            ]);

        if (function_exists('proc_open')) {
            dashboard_menu()->registerItem([
                'id'          => 'cms-core-system-cache',
                'priority'    => 6,
                'parent_id'   => 'cms-core-platform-administration',
                'name'        => 'Base::cache.cache_management',
                'icon'        => null,
                'url'         => route('system.cache'),
                'permissions' => [ACL_ROLE_SUPER_USER],
            ]);
        }
    }
}
