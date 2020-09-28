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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'Api\PassportController@login')->name('api.auth.login');
Route::post('token', 'Api\PassportController@token')->name('api.auth.token');
Route::post('register', 'Api\PassportController@register')->name('api.auth.register');

Route::get('categories', 'Api\CategoryController@index')->name('api.categories.index');
Route::get('cuisines/discovery', 'Api\CuisineController@discovery')->name('api.cuisines.discovery');
Route::get('cuisines/nearby', 'Api\CuisineController@nearby')->name('api.cuisines.nearby');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'Api\PassportController@user')->name('api.user.profile');
    Route::post('logout', 'Api\PassportController@logout')->name('api.auth.logout');

    Route::get('user/orders', 'Api\UserController@orders')->name('api.user.orders');
    Route::get('user/transactions', 'Api\UserController@transactions')->name('api.user.transactions');
});
