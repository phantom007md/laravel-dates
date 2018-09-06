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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register/login', 'RegisterController@login')->name('register.login');
Route::post('/register/store', 'RegisterController@store')->name('register.store');

Route::get('/payments', 'PaymentController@index')->name('payments.index');
Route::post('/payments/pay', 'PaymentController@pay')->name('payments.pay');
Route::get('/payments/verify', 'PaymentController@verify')->name('payments.verify');

Route::apiResources([
    'dates' => 'DateController',
    'users' => 'UserController',
    'topics' => 'TopicController',
]);
