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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('signup')->group(function () {
    Route::post('/account', 'App\Http\Controllers\API\UserSignupController@account');
    Route::post('/personal', 'App\Http\Controllers\API\UserSignupController@personal');
    Route::post('/image', 'App\Http\Controllers\API\UserSignupController@image');
    Route::post('/finish', 'App\Http\Controllers\API\UserSignupController@finish');
});