<?php


namespace Modules\Core\Foundation\Asset\Manager;


use Illuminate\Support\Collection;
use Modules\Core\Exceptions\AssetNotFoundException;

final class BeastAssetManager implements AssetManager
{

    /**
     * @var array|\Illuminate\Support\Collection
     */
    protected   $css = [];
    /**
     * @var array|\Illuminate\Support\Collection
     */
    protected   $js = [];

    /**
     * BeastAssetManager constructor.
     */
    public function __construct()
    {
        $this->css = new Collection();
        $this->js  = new Collection();
    }

    /**
     * Add a possible asset
     * @param $dependency
     * @param $path
     *
     * @return \Illuminate\Support\Collection
     */
    public function addAsset($dependency, $path)
    {
        if($this->isJs($path)) {
            return $this->js->put($dependency, $path);
        }
        if($this->isCss($path)) {
            return $this->css->put($dependency, $path);
        }
    }

    /**
     * Add an array of possible assets
     * @param  array  $asset
     * @return void
     */
    public function addAssets(array $asset)
    {
        if(is_array($asset) && !empty($asset)) {
            foreach ($asset as $dependency => $path){
                $this->addAsset($dependency, $path);
            }
        }
    }

    /**
     * Return all css files to include
     * @return array|\Illuminate\Support\Collection
     */
    public function allCss()
    {
        return $this->css;
    }

    /**
     * Return all js files to include
     * @return array|\Illuminate\Support\Collection
     */
    public function allJs()
    {
        return $this->js;
    }

    /**
     * @param $dependency
     *
     * @return mixed
     */
    public function getJs($dependency)
    {
        $assetPath = $this->js->get($dependency);
        try {
            $this->guardForAssetNotFound($assetPath);
        } catch (AssetNotFoundException $e) {
            echo $e->getMessage();
        }
        return $assetPath;
    }

    /**
     * @param $dependency
     *
     * @return mixed
     */
    public function getCss($dependency)
    {
        $assetPath = $this->css->get($dependency);
        try {
            $this->guardForAssetNotFound($assetPath);
        } catch (AssetNotFoundException $e) {
            echo $e->getMessage();
        }
        return $assetPath;
    }

    /**
     * Check if given path is js file
     * @param $path
     *
     * @return bool
     */
    private function isJs($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION) == 'js';
    }

    /**
     * Check if a given path is css file
     * @param $path
     *
     * @return bool
     */
    private function isCss($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION) == 'css';
    }

    /**
     * If asset was not found, throw an exception
     *
     * @param $assetPath
     *
     * @throws \Modules\Core\Exceptions\AssetNotFoundException
     */
    private function guardForAssetNotFound($assetPath)
    {
        if(is_null($assetPath)) {
            throw new AssetNotFoundException($assetPath);
        }
    }
}
