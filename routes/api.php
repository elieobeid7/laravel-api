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

Route::group(['prefix' => 'user'], function () {
    Route::get('subscribe', 'UserController@subscribe');
    Route::get('unsubscribe', 'UserController@unsubscribe');

});


Route::group(['middleware' => 'auth.jwt', 'prefix' => 'server'], function () {

    Route::get('subscribe', [JWTAuthController::class, 'ServerController@subscribe']);
    Route::get('unsubscribe', [JWTAuthController::class, 'ServerController@unsubscribe']);
});
