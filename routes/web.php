<?php

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

Route::get('/', 'MainController@index')->name('main');

// Auth Route

//  login
Route::get('login', 'Auth\LoginController@index')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// register
Route::get('register', 'Auth\RegisterController@index')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::post('register/check-username', 'Auth\RegisterController@checkUsername')->name('register.checkUsername');

// forgot & reset password
Route::get('password/forgot', 'Auth\ForgotPasswordController@index')->name('password.forgot');
Route::post('password/forgot', 'Auth\ForgotPasswordController@forgot');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@index');

// email verification
Route::get('email/verification', 'Auth\VerificationController@index')->name('email.index');
Route::get('email/verification/{id}/{hash}', 'Auth\VerificationController@verify')->name('email.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('email.resend');

Route::name('icons.')->prefix('icons')->group(function () {
    Route::middleware('ajax')->group(function () {
        Route::post('/select2', 'IconController@select2')->name('select2');
    });
});

Route::get('/mail-test', function () {
    return view('mails.auth.new-device-login-notification');
});
