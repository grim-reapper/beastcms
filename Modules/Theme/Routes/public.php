<?php

use Modules\Theme\Events\ThemeRoutingAfterEvent;
use Modules\Theme\Events\ThemeRoutingBeforeEvent;

Route::group(['namespace' => 'Modules\Theme\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        event(new ThemeRoutingBeforeEvent);

        Route::get('/', [
            'as'   => 'public.index',
            'uses' => 'PublicController@getIndex',
        ]);

        Route::get('sitemap.xml', [
            'as'   => 'public.sitemap',
            'uses' => 'PublicController@getSiteMap',
        ]);

        Route::get('{slug?}' . config('Base.general.public_single_ending_url'), [
            'as'   => 'public.single',
            'uses' => 'PublicController@getView',
        ]);

        event(new ThemeRoutingAfterEvent);
    });
});
