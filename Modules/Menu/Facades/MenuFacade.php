<?php

namespace Modules\Menu\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Menu\Menu;

class MenuFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    protected static function getFacadeAccessor()
    {
        return Menu::class;
    }
}
