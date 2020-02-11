<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Modules\Base\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('Base.config.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/info'], function () {
            Route::get('', [
                'as'         => 'system.info',
                'uses'       => 'SystemController@getInfo',
                'permission' => ACL_ROLE_SUPER_USER,
            ]);
        });

        Route::group(['prefix' => 'system/cache'], function () {

            Route::get('', [
                'as'         => 'system.cache',
                'uses'       => 'SystemController@getCacheManagement',
                'permission' => ACL_ROLE_SUPER_USER,
            ]);

            Route::post('clear', [
                'as'         => 'system.cache.clear',
                'uses'       => 'SystemController@postClearCache',
                'permission' => ACL_ROLE_SUPER_USER,
            ]);
        });

        Route::post('membership/authorize', [
            'as'         => 'membership.authorize',
            'uses'       => 'SystemController@authorize',
            'permission' => ACL_ROLE_SUPER_USER,
        ]);
    });
});
