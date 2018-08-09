<?php

Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::group(['middleware' => 'web', 'namespace' => 'Webaccess\IFMQuiz\Http\Controllers'], function () {

    //FRONT
    Route::get('/introduction/{uuid}', array('as' => 'quiz_front_intro', 'uses' => 'FrontController@quiz_intro'));
    Route::post('/introduction/{uuid}', array('as' => 'quiz_front_intro_handler', 'uses' => 'FrontController@quiz_front_intro_handler'));
    Route::get('/{uuid}', array('as' => 'quiz_front', 'uses' => 'FrontController@quiz'));
    Route::post('/{uuid}/submit', array('as' => 'quiz_front_handler', 'uses' => 'FrontController@quiz_handler'));
    Route::get('/remerciements/{uuid}', array('as' => 'quiz_front_outro', 'uses' => 'FrontController@quiz_outro'));

    //ADMIN
    Route::get('/admin/login', array('as' => 'login', 'uses' => 'LoginController@index'));
    Route::get('/admin/logout', array('as' => 'logout', 'uses' => 'LoginController@logout'));
    Route::post('/admin/login_handler', array('as' => 'login_handler', 'uses' => 'LoginController@login'));

    Route::group(['middleware' => ['admin']], function () {
        Route::get('/admin', array('as' => 'quiz_list', 'uses' => 'QuizController@index'));
        Route::get('/admin/questionnaires/creer/{type}', array('as' => 'quiz_create', 'uses' => 'QuizController@create'));
        Route::get('/admin/questionnaires/modifier/{uuid}', array('as' => 'quiz_update', 'uses' => 'QuizController@update'));
        Route::get('/admin/questionnaires/resultats/{uuid}', array('as' => 'quiz_results', 'uses' => 'QuizController@results'));
        Route::get('/admin/questionnaires/resultats/{uuid}/reponses/{attempt_uuid}', array('as' => 'quiz_user_answers', 'uses' => 'QuizController@user_answers'));
        Route::post('/admin/questionnaires/resultats/{uuid}/reponses/{attempt_uuid}', array('as' => 'quiz_user_answers_valid_answer', 'uses' => 'QuizController@user_answers_valid_answer'));
        Route::get('/admin/questionnaires/parametres/{uuid}', array('as' => 'quiz_parameters', 'uses' => 'QuizController@parameters'));
        Route::post('/admin/questionnaires/parametres/{uuid}', array('as' => 'quiz_parameters_handler', 'uses' => 'QuizController@parameters_handler'));
        Route::get('/admin/questionnaires/envoyer/{uuid}', array('as' => 'quiz_mailing', 'uses' => 'QuizController@mailing'));
        Route::post('/admin/questionnaires/envoyer/{uuid}', array('as' => 'quiz_mailing_handler', 'uses' => 'QuizController@mailing_handler'));
        Route::get('/admin/questionnaires/dupliquer/{uuid}', array('as' => 'quiz_duplicate', 'uses' => 'QuizController@duplicate'));
        Route::get('/admin/questionnaires/supprimer/{uuid}', array('as' => 'quiz_delete', 'uses' => 'QuizController@delete'));

        Route::get('/admin/utilisateurs', array('as' => 'user_list', 'uses' => 'UserController@index'));
        Route::get('/admin/utilisateurs/creer', array('as' => 'user_create', 'uses' => 'UserController@create'));
        Route::post('/admin/utilisateurs/creer', array('as' => 'user_create_handler', 'uses' => 'UserController@create_handler'));
        Route::get('/admin/utilisateurs/modifier/{uuid}', array('as' => 'user_update', 'uses' => 'UserController@update'));
        Route::post('/admin/utilisateurs/modifier', array('as' => 'user_update_handler', 'uses' => 'UserController@update_handler'));
        Route::get('/admin/utilisateurs/supprimer/{uuid}', array('as' => 'user_delete', 'uses' => 'UserController@delete'));

        //API
        Route::get('/admin/quiz/{uuid}', array('as' => 'quiz', 'uses' => 'QuizController@quiz'));
        Route::post('/admin/quiz/{uuid}', array('as' => 'quiz_handler', 'uses' => 'QuizController@quiz_handler'));
        Route::post('/admin/quiz/{uuid}/upload_image/{image_type}', array('as' => 'quiz_upload_image', 'uses' => 'QuizController@quiz_upload_image'));
        Route::post('/admin/quiz/{uuid}/delete_image/{image_type}', array('as' => 'quiz_delete_image', 'uses' => 'QuizController@quiz_delete_image'));
    });
});