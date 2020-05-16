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

Route::post('/v1/step/login', '\App\Http\Controllers\Api\V1\StepController@Login');
Route::post('/v1/step/setStep', '\App\Http\Controllers\Api\V1\StepController@setStep');
Route::post('/v1/step/updateStep', '\App\Http\Controllers\Api\V1\StepController@updateStep');
Route::post('/v1/step/setLocations', '\App\Http\Controllers\Api\V1\StepController@setLocations');
Route::get('/v1/step/getStep', '\App\Http\Controllers\Api\V1\StepController@getStep');
Route::get('/v1/step/pushApp', '\App\Http\Controllers\Api\V1\StepController@pushApp');
Route::get('/v1/step/test', '\App\Http\Controllers\Api\V1\StepController@test');
