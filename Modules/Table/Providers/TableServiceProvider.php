<?php

namespace Modules\Table\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Base\Traits\LoadAndPublishDataTrait;

class TableServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setNamespace('Table')
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();
    }
}
