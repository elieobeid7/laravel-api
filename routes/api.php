<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServerController;


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
    Route::get('subscribe', [UserController::class,'subscribe']);
    Route::get('unsubscribe', [UserController::class,'unsubscribe']);

});


Route::group([ 'prefix' => 'server'], function () {

    Route::get('subscribe', [ServerController::class,'subscribe']);
    Route::get('unsubscribe', [ServerController::class,'unsubscribe']);
});
