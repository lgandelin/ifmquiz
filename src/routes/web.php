<?php

Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::group(['middleware' => 'web', 'namespace' => 'Webaccess\IFMQuiz\Http\Controllers'], function () {

    //DASHBOARD
    Route::get('/', array('as' => 'index', 'uses' => 'IndexController@index'));

    Route::group(['middleware' => 'admin'], function () {
        
    });
});