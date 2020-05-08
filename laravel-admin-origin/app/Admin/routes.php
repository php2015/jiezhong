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
    $router->resource('organizations', OrganizationController::class);
    $router->resource('organization-peoples', OrganizationPeopleController::class);
    $router->resource('users', UserController::class);
    $router->resource('set-classes', SetClassController::class);
    $router->resource('vaccines', VaccineController::class);
    $router->resource('bookings', BookingController::class);











});
