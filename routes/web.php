<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('maintenance.frontend')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::get('/email/courier-verify/{id}/{hash}', 'Management\CourierController@verify')
    ->middleware(['signed', 'throttle:6,1'])
    ->name('courier.verify');

Route::middleware(['auth', 'verified', 'can:access-dashboard'])->name('admin.')->group(function() {
    Route::get('dashboard', 'Management\DashboardController@index')->name('dashboard');

    Route::middleware('password.confirm')->group(function() {
        Route::get('account', 'Management\AccountController@index')->name('account');
        Route::get('settings', 'Management\SettingController@index')->name('settings');
        Route::put('settings', 'Management\SettingController@update')->name('settings.update');
    });

    Route::resources([
        'groups' => 'Management\GroupController',
        'users' => 'Management\UserController',
        'categories' => 'Management\CategoryController',
        'restaurants' => 'Management\RestaurantController',
        'cuisines' => 'Management\CuisineController',
        'couriers' => 'Management\CourierController',
    ]);
});
