<?php


namespace Modules\Setting\Facades;
use Illuminate\Support\Facades\Facade;
use Modules\Setting\Support\SettingStore;

class SettingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SettingStore::class;
    }
}
