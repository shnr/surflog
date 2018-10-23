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


/*
  Reference:  
  https://readouble.com/laravel/5.7/ja/controllers.html#resource-controllers
*/
Route::group(['middleware' => 'auth:api'], function () {

  // 全体を設定
  Route::resource('tasks',  'TaskController');
  Route::resource('conditions',  'ConditionController');

});



// 認証関連のルーティング
// 参考は/vendor/laravel/framework/src/Illuminate/Routing/Router.php
// のauth()

// Route::get('registerhoge', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('registerhoge', 'Auth\RegisterController@register');
