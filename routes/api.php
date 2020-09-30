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
Route::get('cuisines/{id}', 'Api\CuisineController@show')->name('api.cuisines.show');

Route::get('restaurants/discovery', 'Api\RestaurantController@discovery')->name('api.restaurants.discovery');
Route::get('restaurants/{id}', 'Api\RestaurantController@show')->name('api.restaurants.show')->where('id', '[0-9]+');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'Api\PassportController@user')->name('api.user.account');
    Route::post('logout', 'Api\PassportController@logout')->name('api.auth.logout');

    Route::get('user/profile', 'Api\UserController@profile')->name('api.user.profile');
    Route::post('user/profile', 'Api\UserController@updateProfile')->name('api.user.update');
    Route::post('user/photo', 'Api\UserController@updatePhoto')->name('api.user.photo');
    Route::get('user/orders', 'Api\UserController@orders')->name('api.user.orders');
    Route::get('user/transactions', 'Api\UserController@transactions')->name('api.user.transactions');
    Route::get('restaurants/orders', 'Api\RestaurantController@orders')->name('api.restaurant.order');

    Route::post('orders', 'Api\OrderController@store')->name('api.order.store');
    Route::put('orders/{id}/take/{courier}', 'Api\OrderController@takeOrder')->name('api.order.take')->where(['id' => '[0-9]+', 'courier' => '[0-9]+']);
    Route::put('orders/{order}/status', 'Api\OrderController@updateStatus')->name('api.order.status')->where('order', '[0-9]+');
    Route::put('orders/{order}/rate', 'Api\OrderController@rateOrder')->name('api.order.rate')->where('order', '[0-9]+');
});
