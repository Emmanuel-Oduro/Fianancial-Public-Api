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

Route::post('login', 'APi\ApiController@login');
Route::post('register', 'APi\ApiController@register');
// Route::get('login', 'APi\ApiController@loginForm')->name('loginForm');
// Route::get('register', 'APi\ApiController@registerForm')->name('registerForm');

// Auth::routes();
Route::middleware('auth:api')->group(function () {
    Route::get('profile', 'APi\ApiController@userdetails');
    Route::post('product_category', 'APi\ApiController@ProductCategory');
    Route::resource('user', 'APi\ApiController');
    Route::put('approve_status/{id}', 'APi\ApiController@ApproveStatus');
    Route::put('reject_status/{id}', 'APi\ApiController@RejectStatus');
    Route::resource('products', 'APi\ProductController');
    Route::resource('salary', 'APi\SalaryController');
    Route::resource('bonus', 'APi\BonusController');

    Route::get('dashboard', 'APi\ApiController@Home');
    Route::get('history/{id}', 'APi\SalaryController@UserSalaryHistory');


});
