<?php

namespace Modules\Media\Providers;

use Modules\Base\Supports\Helper;
use Modules\Base\Traits\LoadAndPublishDataTrait;
use Modules\Media\Commands\DeleteThumbnailCommand;
use Modules\Media\Commands\GenerateThumbnailCommand;
use Modules\Media\Facades\RvMediaFacade;
use Modules\Media\Entities\MediaFile;
use Modules\Media\Entities\MediaFolder;
use Modules\Media\Entities\MediaSetting;
use Modules\Media\Repositories\Caches\MediaFileCacheDecorator;
use Modules\Media\Repositories\Caches\MediaFolderCacheDecorator;
use Modules\Media\Repositories\Caches\MediaSettingCacheDecorator;
use Modules\Media\Repositories\Eloquent\MediaFileRepository;
use Modules\Media\Repositories\Eloquent\MediaFolderRepository;
use Modules\Media\Repositories\Eloquent\MediaSettingRepository;
use Modules\Media\Repositories\Interfaces\MediaFileInterface;
use Modules\Media\Repositories\Interfaces\MediaFolderInterface;
use Modules\Media\Repositories\Interfaces\MediaSettingInterface;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:50 AM
 */
class MediaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        Helper::autoload(__DIR__ . '/../Helpers');

        $this->app->bind(MediaFileInterface::class, function () {
            return new MediaFileCacheDecorator(
                new MediaFileRepository(new MediaFile),
                MEDIA_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(MediaFolderInterface::class, function () {
            return new MediaFolderCacheDecorator(
                new MediaFolderRepository(new MediaFolder),
                MEDIA_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(MediaSettingInterface::class, function () {
            return new MediaSettingCacheDecorator(
                new MediaSettingRepository(new MediaSetting)
            );
        });

        AliasLoader::getInstance()->alias('RvMedia', RvMediaFacade::class);
    }

    public function boot()
    {
        $this->setNamespace('Media')
            ->loadAndPublishConfigurations(['permissions', 'media'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-core-media',
                'priority'    => 995,
                'parent_id'   => null,
                'name'        => 'Media::media.menu_name',
                'icon'        => 'far fa-images',
                'url'         => route('media.index'),
                'permissions' => ['media.index'],
            ]);
        });

        $this->commands([
            GenerateThumbnailCommand::class,
            DeleteThumbnailCommand::class,
        ]);
    }
}
