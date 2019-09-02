<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
//get
Route::get('test', 'api/test/index');


Route::resource('test', 'api/test');
/// x.com/test  post  => api test save

Route::get('api/:version/cat', 'api/:version.cat/read');
Route::get('api/:version/index', 'api/:version.index/index');
Route::get('api/:version/init', 'api/:version.index/init');

// news
Route::get('api/:version/newslist', 'api/:version.news/index');
//排行
Route::get('api/:version/news/rank', 'api/:version.news/rank');
Route::get('api/:version/news', 'api/:version.news/read');
//短信验证码相关
Route::resource('api/:version/identify', 'api/:version.identify');