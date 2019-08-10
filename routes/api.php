<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    //api user
    Route::group(['prefix'=>'user'], function(){
        Route::post('login', 'UserController@login');
        Route::post('register', 'UserController@register');
        Route::post('request_access_token','UserController@requestAccessToken');
        

        Route::group(['middleware'=>'accessTokenValidator'],function(){
            Route::post('update','UserController@update');    
            Route::post('check_validate','UserController@check_validate');
            Route::post('validate_user','UserController@validate_user');
            Route::post('update_setting','UserController@update_setting');
        });

    });

    Route::group(['prefix'=>'level' , 'middleware' => 'accessTokenValidator'], function(){
        Route::post('last_level','GameController@set_last_level');
        Route::get('last_level','GameController@get_last_level');
        Route::get('all','GameController@get_all_level');
       
    });
    Route::group(['prefix'=>'leaderboard','middleware' => 'accessTokenValidator'],function(){

        Route::get('all/{level_id}/{total}','GameController@get_leaderboard');
        Route::get('inbetween/{level_id}','GameController@get_inbetween_leadeboard');
        Route::get('get_current_record/{level_id}','GameController@get_current_record');
    });
    
    
    Route::group(['prefix'=>'session', 'middleware'=>'accessTokenValidator'],function(){
        Route::post('finish','GameController@finish_game_session');
        Route::get('history/all','GameController@get_all_history_level');
        Route::get('history/{level_id}','GameController@get_history_level');
    });

    Route::group(['prefix'=>'statistic', 'middleware'=>'accessTokenValidator'],function(){
        Route::get('all','StatisticController@getAllStatistic');
        Route::post('set','StatisticController@setStatistic');
    });



});

