<?php


namespace Modules\Core\Traits;
use Illuminate\Support\Str;

trait LoadAndPublishDataTrait
{
    protected $namespace = null;
    protected $basePath = null;

    public function setNamespace($namespace): self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function setBasePath($path): self
    {
        $this->basePath = $path;
        return $this;
    }

    public function getBasePath(): string
    {
        return $this->basePath ?? module_path();
    }

    public function loadAndPublishConfiguration($fileNames): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }
        foreach ($fileNames as $fileName) {
            $this->mergeConfigFrom(
                $this->getConfigFilePath($fileName),
                $this->getDotedNamespace() . '.' . $fileName
            );
            if ($this->app->runningInConsole()) {
                $this->publishes(
                    [
                        $this->getConfigFilePath($fileName) => config_path(
                            $this->getDashedNamespace() . '/' . $fileName . '.php'
                        ),
                    ],
                    'cms-config'
                );
            }
        }
        return $this;
    }

    public function loadRoutes($fileNames = ['web']): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }
        foreach ($fileNames as $fileName) {
            $this->loadRoutesFrom($this->getRouteFilePath($fileName));
        }
        return $this;
    }

    public function loadAndPublishViews(): self
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDashedNamespace());
        if ($this->app->runninInConsole()) {
            $this->publishes(
                [
                    $this->getViewsPath() => resource_path(
                        'views/vendor/' . $this->getDashedNamespace()
                    ),
                ],
                'cms-views'
            );
        }
        return $this;
    }

    protected function loadAndPublishTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDashedNamespace());
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->getTranslationsPath() => resource_path('lang/vendor/' . $this->getDashedNamespace())],
                             'cms-lang');
        }

        return $this;
    }

    public function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());
        return $this;
    }

    public function publishPublicFolder($path = null): self
    {
        return $this->publishAssets($path);
    }

    /**
     * @param null $path
     * @return $this
     * @deprecated
     */
    public function publishAssetsFolder(): self
    {
        return $this->publishAssets();
    }

    /**
     * @param null $path
     * @return $this
     */
    public function publishAssets($path = null): self
    {
        if ($this->app->runningInConsole()) {
            if (empty($path)) {
                $path = !Str::contains($this->getDotedNamespace(),
                                       'core.') ? 'vendor/core/' . $this->getDashedNamespace() : 'vendor/core';
            }
            $this->publishes([$this->getAssetsPath() => public_path($path)], 'cms-public');
        }

        return $this;
    }

    protected function getMigrationsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/database/migrations/';
    }

    protected function getTranslationsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/lang/';
    }

    protected function getViewsPath(): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/resources/views/';
    }
    protected function getRouteFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/routes/' . $file . '.php';
    }

    protected function getConfigFilePath($file): string
    {
        return $this->getBasePath() . $this->getDashedNamespace() . '/config/' . $file . '.php';
    }

    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }
}
