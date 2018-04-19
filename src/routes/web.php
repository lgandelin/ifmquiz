<?php

Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::group(['middleware' => 'web', 'namespace' => 'Webaccess\IFMQuiz\Http\Controllers'], function () {

    Route::get('/dashboard', array('as' => 'index', 'uses' => 'QuizController@index'));
    Route::get('/questionnaires/creer', array('as' => 'quiz_create', 'uses' => 'QuizController@create'));
    Route::get('/questionnaires/modifier/{uuid}', array('as' => 'quiz_update', 'uses' => 'QuizController@update'));
    Route::get('/questionnaires/resultats/{uuid}', array('as' => 'quiz_results', 'uses' => 'QuizController@results'));
    Route::post('/questionnaires/dupliquer/{uuid}', array('as' => 'quiz_duplicate', 'uses' => 'QuizController@duplicate'));
    Route::get('/questionnaires/supprimer/{uuid}', array('as' => 'quiz_delete', 'uses' => 'QuizController@delete'));

    //API
    Route::get('/quiz/{uuid}', array('as' => 'quiz', 'uses' => 'QuizController@quiz'));
    Route::post('/quiz/{uuid}', array('as' => 'quiz_handler', 'uses' => 'QuizController@quiz_handler'));

    Route::group(['middleware' => 'admin'], function () {

    });
});