<?php

namespace Modules\Acl\Providers;

use Modules\Acl\Http\Middleware\Authenticate;
use Modules\Acl\Http\Middleware\RedirectIfAuthenticated;
use Modules\Acl\Entities\Activation;
use Modules\Acl\Entities\Role;
use Modules\Acl\Entities\User;
use Modules\Acl\Repositories\Caches\RoleCacheDecorator;
use Modules\Acl\Repositories\Eloquent\ActivationRepository;
use Modules\Acl\Repositories\Eloquent\RoleRepository;
use Modules\Acl\Repositories\Eloquent\UserRepository;
use Modules\Acl\Repositories\Interfaces\ActivationInterface;
use Modules\Acl\Repositories\Interfaces\RoleInterface;
use Modules\Acl\Repositories\Interfaces\UserInterface;
use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Event;
use Exception;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);

        $this->setNamespace('Acl')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes(['web'])
            ->loadMigrations();

        config()->set(['auth.providers.users.model' => User::class]);

        $this->app->register(HookServiceProvider::class);

        $this->garbageCollect();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                                   'id'          => 'cms-core-role-permission',
                                   'priority'    => 2,
                                   'parent_id'   => 'cms-core-platform-administration',
                                   'name'        => 'Acl::permissions.role_permission',
                                   'icon'        => null,
                                   'url'         => route('roles.index'),
                                   'permissions' => ['roles.index'],
                               ])
                ->registerItem([
                                   'id'          => 'cms-core-user',
                                   'priority'    => 3,
                                   'parent_id'   => 'cms-core-platform-administration',
                                   'name'        => 'Acl::users.users',
                                   'icon'        => null,
                                   'url'         => route('users.index'),
                                   'permissions' => ['users.index'],
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
        $router = $this->app['router'];

        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);

        $this->app->bind(UserInterface::class, function () {
            return new UserRepository(new User);
        });

        $this->app->bind(ActivationInterface::class, function () {
            return new ActivationRepository(new Activation);
        });

        $this->app->bind(RoleInterface::class, function () {
            return new RoleCacheDecorator(new RoleRepository(new Role));
        });

        Helper::autoload(__DIR__ . '/../helpers');
    }

    /**
     * Garbage collect activations and reminders.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function garbageCollect()
    {
        $config = $this->app->make('config')->get('Acl.general');

        $this->sweep($this->app->make(ActivationInterface::class), $config['activations']['lottery']);
    }

    /**
     * Sweep expired codes.
     *
     * @param mixed $repository
     * @param array $lottery
     * @return void
     */
    protected function sweep($repository, array $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param array $lottery
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
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
