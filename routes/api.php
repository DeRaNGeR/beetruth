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

Route::post('login', 'AuthController@login')->name('login');
Route::post('forgot', 'AuthController@forgot')->name('forgot');
Route::post('register', 'AuthController@register')->name('register');

Route::middleware(['jwt.auth'])->group(function() {
    Route::get('/products', 'FoodController@index')->name('products.index');
    Route::get('/products/comparison/{product_a}', 'FoodController@show')->name('products.show');
    Route::post('/products', 'FoodController@store')->name('products.store');
    Route::get('/me', 'UserController@show')->name('users.show');
    Route::put('/me', 'UserController@update')->name('users.update');
    Route::get('/rewards', 'RewardController@index')->name('rewards.index');
});
