<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::group(['middleware' => 'auth:api','namespace' => 'Api\V1\Auth','prefix' => 'v1'], function ($router) {
    Route::apiResource('profile', ProfileController::class)->only(['index','store']);
    Route::apiResource('user-detail', UserDetailController::class)->only(['index','store']);
});

Route::group(['namespace' => 'Api\V1\Auth', 'prefix' => 'v1'],function () {
    Route::post('register', 'AuthController@store')->name('register.store');
    Route::post('login', 'AuthController@login')->name('register.login');
});

