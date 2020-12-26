<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JWTAuthController;

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

Route::get('subscribe', [JWTAuthController::class, 'UserController@subscribe']);
Route::get('unsubscribe', [JWTAuthController::class, 'UserController@unsubscribe']);


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('unsubscribeCallback', [JWTAuthController::class, 'ServerController@unsubscribeCallback']);
    Route::get('subscribeCallback', [JWTAuthController::class, 'ServerController@subscribeCallback']);
});
