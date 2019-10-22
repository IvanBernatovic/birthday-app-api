<?php

use Illuminate\Http\Request;

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
Route::group([

    'middleware' => 'api'

], function ($router) {

    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('birthdays', 'BirthdayController@index')->name('birthdays');
        Route::post('birthdays', 'BirthdayController@store')->name('birthdays.store');
        Route::patch('birthdays/{birthday}', 'BirthdayController@update')->name('birthdays.update');
        Route::delete('birthdays/{birthday}', 'BirthdayController@delete')->name('birthdays.delete');
    });

});
