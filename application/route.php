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




//Sample
Route::get('api/:version/sample/:key', 'api/:version.Sample/getSample');
Route::post('api/:version/sample/test3', 'api/:version.Sample/test3');

//Miss 404
//Miss 路由开启后，默认的普通模式也将无法访问
//Route::miss('api/v1.Miss/miss');

//Banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');



Route::group('api/:version/theme',function(){
    Route::get('', 'api/:version.Theme/getSimpleList');
    Route::get('/:id', 'api/:version.Theme/getComplexOne');
    Route::post(':t_id/product/:p_id', 'api/:version.Theme/addThemeProduct');
    Route::delete(':t_id/product/:p_id', 'api/:version.Theme/deleteThemeProduct');
});

//Product
Route::group('api/:version/product',function (){
    Route::post('', 'api/:version.Product/createOne');
    Route::delete('/:id', 'api/:version.Product/deleteOne');
    Route::get('/by_category/paginate', 'api/:version.Product/getByCategory');
    Route::get('/by_category', 'api/:version.Product/getAllInCategory');
    Route::get('/:id', 'api/:version.Product/getOne',[],['id'=>'\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});
//Category
Route::group('api/:version/category',function (){
    Route::get('', 'api/:version.Category/getCategories');
    Route::get('/all', 'api/:version.Category/getAllCategories');
});


//Token
Route::group('api/:version/token',function (){
    Route::post('/user', 'api/:version.Token/getToken');
    Route::post('/app', 'api/:version.Token/getAppToken');
    Route::post('/verify', 'api/:version.Token/verifyToken');
});
//Token
Route::group('api/:version/user',function (){
    Route::post('/wx_info', 'api/:version.User/edit');
});
//Address
Route::group('api/:version/address',function (){
    Route::post('', 'api/:version.Address/createOrUpdateAddress');
    Route::get('', 'api/:version.Address/getUserAddress');
});

//Order
Route::group('api/:version/order',function (){
    Route::post('', 'api/:version.Order/placeOrder');
    Route::get('/:id', 'api/:version.Order/getDetail',[], ['id'=>'\d+']);
    Route::put('/delivery', 'api/:version.Order/delivery');
    Route::get('/by_user', 'api/:version.Order/getSummaryByUser');
    Route::get('/paginate', 'api/:version.Order/getSummary');
});

//Pay
Route::group('api/:version/pay',function (){
    Route::post('/pre_order', 'api/:version.Pay/getPreOrder');
    Route::post('/notify', 'api/:version.Pay/receiveNotify');
    Route::post('/re_notify', 'api/:version.Pay/redirectNotify');
    Route::post('/concurrency', 'api/:version.Pay/notifyConcurrency');
});
//Message
Route::post('api/:version/message/delivery', 'api/:version.Message/sendDeliveryMsg');