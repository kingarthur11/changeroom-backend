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

Route::middleware('auth:user-api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:user-api'], function () {
    Route::resource('companies', App\Http\Controllers\API\CompanyAPIController::class);

    Route::resource('services', App\Http\Controllers\API\ServiceAPIController::class);

    Route::resource('countries', App\Http\Controllers\API\CountryAPIController::class);

    Route::resource('user-auths', App\Http\Controllers\API\UserAuthAPIController::class);
});



Route::post('register', 'UserAuthAPIController@register');
Route::post('login', 'UserAuthAPIController@login');
