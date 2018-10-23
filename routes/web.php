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

Route::get('/', function () {
    return view('app');
});



Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/tasks', 'TaskController@index');
// Route::post('/task', 'TaskController@store');
// Route::delete('/task/{task}', 'TaskController@destroy');



// 認証関連のルーティング
// 今回はAPIを利用するので使わない
// Auth::routes();


/*

URLをカスタムする場合は、
/vendor/laravel/framework/src/Illuminate/Routing/Router.php
のauth()内の記述を持ってくる

*/
// Route::get('loginnnnn', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('loginnnnn', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Route::get('registerhoge', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('registerhoge', 'Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Route::emailVerification();
