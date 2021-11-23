<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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
*/Route::group([
    'middleware' => 'api'

], function () {
   Route::group(['prefix' => 'user'], function () {
    Route::post('/',[AuthController::class ,'register']);
    Route::get('/', [AuthController::class ,'login']);
    Route::post('/logout',[AuthController::class ,'logout']);

    });

    Route::group(['prefix' => 'supermaker'], function () {
        Route::get('/', [ProductController::class, 'read']);
        Route::post('/', [ProductController::class ,'create']);//
        Route::delete('/', [ProductController::class ,'delete']);
        Route::patch('/', [ProductController::class ,'update']);

    });


    //Route::post('refresh', 'AuthController@refresh');
    //Route::post('me', 'AuthController@me');

});
