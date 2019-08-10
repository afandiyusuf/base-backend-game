<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('statistic-player', PlayerStatisticViewController::class)->only([
        'index'
    ]);
    $router->get('/users/ban/{user}','UserController@ban');
    $router->get('/users/activate/{user}','UserController@activate');
    $router->resource('statistic', StatisticController::class);
    $router->resource('leaderboard', LeaderboardController::class);
    $router->resource('level', LevelController::class);
});
