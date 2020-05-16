<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/word', 'HomeController@word');

    $router->resource('users', UserController::class);
    $router->resource('types', TypeController::class);
    $router->resource('steps', StepController::class);
    $router->resource('locations', LocationController::class);
    $router->resource('be-on-duties', beOnDutyController::class);
















});
