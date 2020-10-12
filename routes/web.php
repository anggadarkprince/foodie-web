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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('home.index');
})->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified', 'can:access-dashboard'])->name('admin.')->group(function() {
    Route::get('dashboard', function () {
        return view('home.index');
    })->name('dashboard');

    Route::get('account', function () {
        return view('account.index', ['user' => request()->user()]);
    })->middleware('password.confirm')->name('account');

    Route::resources([
        'groups' => 'Management\GroupController',
        'users' => 'Management\UserController',
    ]);
});
