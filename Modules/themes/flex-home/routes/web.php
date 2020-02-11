<?php

Theme::routes();

Route::group(['namespace' => 'Theme\FlexHome\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('/', 'FlexHomeController@getIndex')->name('public.index');

        Route::get('sitemap.xml', [
            'as'   => 'public.sitemap',
            'uses' => 'FlexHomeController@getSiteMap',
        ]);

        Route::get('{slug?}' . config('Base.general.public_single_ending_url'), [
            'as'   => 'public.single',
            'uses' => 'FlexHomeController@getView',
        ]);

    });

});
