<?php

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
Route::post('register', 'AuthController@register');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
Route::get('me', 'AuthController@me');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::patch('me', 'UserController@update')->name('users.update');

    Route::get('reminders', 'ReminderController@index')->name('reminders');
    Route::post('reminders', 'ReminderController@store')->name('reminders.store');
    Route::delete('reminders/{reminder}', 'ReminderController@delete')->name('reminders.delete');

    Route::get('birthdays', 'BirthdayController@index')->name('birthdays');
    Route::post('birthdays', 'BirthdayController@store')->name('birthdays.store');
    Route::put('birthdays/{birthday}', 'BirthdayController@update')->name('birthdays.update');
    Route::delete('birthdays/{birthday}', 'BirthdayController@delete')->name('birthdays.delete');

    Route::post('/birthdays/{birthday}/gifts', 'BirthdayGiftsController@store')->name('gifts.store');
    Route::delete('/birthdays/{birthday}/gifts/{gift}', 'BirthdayGiftsController@delete')->name('gifts.delete');

    Route::post('/birthdays/{birthday}/reminders', 'BirthdayRemindersController@store')->name('birthday-reminders.store');
    Route::delete('/birthdays/{birthday}/reminders/{reminder}', 'BirthdayRemindersController@delete')->name('birthday-reminders.delete');
});
