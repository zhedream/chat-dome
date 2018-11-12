<?php

use core\Route;

// test
Route::get('/test/test', 'app/controllers/IndexController@test');

Route::get('/', 'app/controllers/IndexController@chat')->middleware('CheckLogin')->name('index.chat'); // 首页聊天
Route::get('/login', 'app/controllers/IndexController@login')->name('index.login'); // 登陆
Route::post('/dologin', 'app/controllers/IndexController@dologin')->name('index.dologin');
Route::get('/regist', 'app/controllers/IndexController@regist')->name('index.regist'); // 注册
Route::post('/doregist', 'app/controllers/IndexController@doregist')->name('index.doregist');

// 后台管理
Route::get('/manage', 'app/controllers/IndexController@manage')->middleware('CheckLogin')->name('index.manage'); // 用户管理
Route::post('/manage/del', 'app/controllers/IndexController@del')->middleware('CheckLogin')->name('index.del'); // 用户管理
// user
Route::get('/user/index','app/controllers/UserController@index')->name('user.index'); // 显示列表
Route::get('/user/search','app/controllers/UserController@search')->name('user.search'); // 搜索
Route::get('/user/add','app/controllers/UserController@add')->name('user.add'); // 显示 添加
Route::post('/user/insert','app/controllers/UserController@insert')->name('user.insert'); // 添加
Route::get('/user/del/{id}','app/controllers/UserController@del')->name('user.del'); // 删除 post
Route::get('/user/mod/{id}','app/controllers/UserController@mod')->name('user.mod'); // 显示 修改
Route::post('/user/update/{id}','app/controllers/UserController@update')->name('user.update'); // 修改
        
