<?php

namespace Modules\Slug\Providers;

use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Slug\Entities\Slug;
use Modules\Slug\Repositories\Caches\SlugCacheDecorator;
use Modules\Slug\Repositories\Eloquent\SlugRepository;
use Modules\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SlugInterface::class, function () {
            return new SlugCacheDecorator(new SlugRepository(new Slug));
        });

        Helper::autoload(__DIR__ . '/../Helpers');
    }

    public function boot()
    {
        $this->setNamespace('Slug')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadRoutes(['web'])
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(FormServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
