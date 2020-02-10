<?php

namespace Modules\Seo\Providers;

use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Seo\Contracts\SeoHelperContract;
use Modules\Seo\Contracts\SeoMetaContract;
use Modules\Seo\Contracts\SeoOpenGraphContract;
use Modules\Seo\Contracts\SeoTwitterContract;
use Modules\Seo\SeoHelper;
use Modules\Seo\SeoMeta;
use Modules\Seo\SeoOpenGraph;
use Modules\Seo\SeoTwitter;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/12/2015 14:09 PM
 */
class SeoServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);

        Helper::autoload(__DIR__ . '/../Helpers');
    }

    public function boot()
    {
        $this->setNamespace('Seo')
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
