<?php

Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::group(['middleware' => 'web', 'namespace' => 'Webaccess\IFMQuiz\Http\Controllers'], function () {

    //FRONT
    Route::get('/{uuid}', array('as' => 'quiz_front', 'uses' => 'FrontController@quiz'));
    Route::post('/{uuid}/submit', array('as' => 'quiz_front_handler', 'uses' => 'FrontController@quiz_handler'));

    //ADMIN
    Route::get('/admin', array('as' => 'quiz_list', 'uses' => 'QuizController@index'));
    Route::get('/admin/questionnaires/creer', array('as' => 'quiz_create', 'uses' => 'QuizController@create'));
    Route::get('/admin/questionnaires/modifier/{uuid}', array('as' => 'quiz_update', 'uses' => 'QuizController@update'));
    Route::get('/admin/questionnaires/resultats/{uuid}', array('as' => 'quiz_results', 'uses' => 'QuizController@results'));
    Route::get('/admin/questionnaires/dupliquer/{uuid}', array('as' => 'quiz_duplicate', 'uses' => 'QuizController@duplicate'));
    Route::get('/admin/questionnaires/supprimer/{uuid}', array('as' => 'quiz_delete', 'uses' => 'QuizController@delete'));

    //API
    Route::get('/quiz/{uuid}', array('as' => 'quiz', 'uses' => 'QuizController@quiz'));
    Route::post('/quiz/{uuid}', array('as' => 'quiz_handler', 'uses' => 'QuizController@quiz_handler'));

    Route::group(['middleware' => 'admin'], function () {

    });
});