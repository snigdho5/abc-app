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

Route::any('login', 'CustomerAPIController@login');
Route::post('register', 'CustomerAPIController@register');
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('details', 'CustomerAPIController@details');
    Route::any('test2', 'NewAPIController@test');
});

Route::any('test', 'NewAPIController@test');

Route::post('send-registration-otp', 'NewAPIController@sendRegOTP');
Route::post('verify-registration', 'NewAPIController@verifyRegOTP');
Route::post('register-user', 'NewAPIController@registerUser');
Route::post('send-login-otp', 'NewAPIController@sendLoginOTP');
Route::post('verify-login-otp', 'NewAPIController@verifyLoginOTP');
Route::post('logout-user', 'NewAPIController@logoutUser');
Route::post('delete-user', 'NewAPIController@deleteUser');
Route::post('loggedin-user', 'NewAPIController@getLoggedinUser'); //not in use
Route::post('user-profile', 'NewAPIController@userProfile');
Route::get('profile-interests', 'NewAPIController@getProfileInterests');
Route::post('update-user-profile', 'NewAPIController@updateUserProfile');
Route::post('update-user-image', 'NewAPIController@updateUserImage');
Route::post('reset-password', 'NewAPIController@resetPassword');

Route::post('send-profile-otp', 'NewAPIController@sendProfileOTP');
Route::post('verify-profile-otp', 'NewAPIController@verifyProfileOTP');

Route::get('notification-logs', 'NewAPIController@getNotification');
Route::post('add-device-fcm', 'NewAPIController@addDeviceFCM');

//clear
Route::get('clear-cache', 'ConfigController@clearCache');
Route::get('clear_cache', function () {

	\Artisan::call('config:clear');
	\Artisan::call('config:cache');

	echo "Cache is cleared";
});