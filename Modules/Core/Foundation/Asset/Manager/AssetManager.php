<?php


namespace Modules\Core\Foundation\Asset\Manager;


interface AssetManager
{
    /**
     * Add a possible asset
     * @param $dependency
     * @param $path
     *
     * @return mixed
     */
    public function addAsset($dependency, $path);

    /**
     * Add an array of possible assets
     * @param  array  $asset
     *
     * @return mixed
     */
    public function addAssets(array $asset);

    /**
     * Return all css files to include
     * @return mixed
     */
    public function allCss();

    /**
     * Return all js files to include
     * @return mixed
     */
    public function allJs();

    /**
     * @param $dependency
     *
     * @return mixed
     */
    public function getJs($dependency);

    /**
     * @param $dependency
     *
     * @return mixed
     */
    public function getCss($dependency);

}
