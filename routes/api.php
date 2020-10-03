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

Route::name('api.')->group(function () {
    Route::post('register', 'Api\PassportController@register')->name('auth.register');
    Route::post('login', 'Api\PassportController@login')->name('auth.login');
    Route::post('token', 'Api\PassportController@token')->name('auth.token');

    Route::get('categories', 'Api\CategoryController@index')->name('categories.index');
    Route::get('cuisines/discovery', 'Api\CuisineController@discovery')->name('cuisines.discovery');
    Route::get('cuisines/{id}', 'Api\CuisineController@show')->name('cuisines.show');

    Route::get('restaurants/discovery', 'Api\RestaurantController@discovery')->name('restaurants.discovery');
    Route::get('restaurants/{id}', 'Api\RestaurantController@show')->name('restaurants.show')->where('id', '[0-9]+');

    Route::middleware('auth:api')->group(function () {
        Route::get('user', 'Api\PassportController@user')->name('user.account');
        Route::post('logout', 'Api\PassportController@logout')->name('auth.logout');

        Route::get('user/profile', 'Api\UserController@profile')->name('user.profile');
        Route::post('user/profile', 'Api\UserController@updateProfile')->name('user.update');
        Route::put('user/photo', 'Api\UserController@updatePhoto')->name('user.photo');
        Route::get('user/orders', 'Api\UserController@orders')->name('user.orders');
        Route::get('user/transactions', 'Api\UserController@transactions')->name('user.transactions');

        Route::post('transactions/deposit', 'Api\TransactionController@deposit')->name('transactions.deposit');
        Route::post('transactions/withdraw', 'Api\TransactionController@withdraw')->name('transactions.withdraw');

        Route::get('restaurants/orders', 'Api\RestaurantController@orders')->name('restaurants.orders');
        Route::get('restaurants/transactions', 'Api\RestaurantController@transactions')->name('restaurants.transactions');
        Route::post('restaurants', 'Api\RestaurantController@update')->name('restaurants.update');

        Route::resource('cuisines', 'Api\CuisineController')->only([
            'store', 'update', 'destroy'
        ]);

        Route::post('orders', 'Api\OrderController@store')->name('order.store');
        Route::put('orders/{order}/confirm', 'Api\OrderController@confirmOrder')->name('order.confirm')->where('order', '[0-9]+');
        Route::put('orders/{order}/served', 'Api\OrderController@serveOrder')->name('order.served')->where('order', '[0-9]+');
        Route::put('orders/{order}/rating', 'Api\OrderController@rateOrder')->name('order.rating')->where('order', '[0-9]+');
    });
});

Route::prefix('courier')->name('api.courier.')->group(function() {
    Route::post('login', 'Api\Courier\SanctumController@login')->name('auth.login');
    Route::post('register', 'Api\Courier\SanctumController@register')->name('auth.register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', 'Api\Courier\SanctumController@user')->name('user.account');
        Route::post('logout', 'Api\Courier\SanctumController@logout')->name('auth.logout');

        Route::get('orders/nearby/{lat}/{lng}', 'Api\Courier\OrderController@nearby')->name('order.nearby');
        Route::put('orders/{id}/take', 'Api\Courier\OrderController@takeOrder')->name('order.take')->where(['id' => '[0-9]+']);
        Route::put('orders/{order}/waiting', 'Api\OrderController@waitingOrder')->name('order.waiting')->where('order', '[0-9]+');
        Route::put('orders/{order}/complete', 'Api\OrderController@completeOrder')->name('order.complete')->where('order', '[0-9]+');
    });
});
