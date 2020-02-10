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

Route::group(['namespace' => 'Modules\PluginManagement\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('Base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'plugins'], function () {
            Route::get('', [
                'as'   => 'plugins.index',
                'uses' => 'PluginManagementController@index',
            ]);

            Route::put('status', [
                'as'         => 'plugins.change.status',
                'uses'       => 'PluginManagementController@update',
                'middleware' => 'preventDemo',
                'permission' => 'plugins.index',
            ]);

            Route::delete('{plugin}', [
                'as'         => 'plugins.remove',
                'uses'       => 'PluginManagementController@destroy',
                'middleware' => 'preventDemo',
                'permission' => 'plugins.index',
            ]);
        });
    });
});
