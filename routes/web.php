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
    return view('welcome');
});

/**
 * Test
 */
Route::prefix('/test')->group(function(){
    Route::get('/cookie','TestController@test1');
});


/**
 * WEB
 */
Route::prefix('/web')->middleware('check.login')->group(function(){
    Route::get('/login','Web\LoginController@loginView');       // 登录
    Route::post('/login','Web\LoginController@login');       // 登录
    Route::get('/logout','Web\LoginController@logout');       // 退出登录
    Route::get('/check/token','Web\LoginController@checkToken');    //验证token
});


