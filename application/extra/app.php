<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/1 0001
 * Time: 20:06
 */
return[
    //app类型
    'apptypes' => [
        'ios',
        'android',
    ],
    //sign多久时间过期
    'app_sign_cache_time'=>20000,
    //验证sign只能使用一次，这个时间要比app_sign_cache_time长点
    'app_sign_time'=>20050
];