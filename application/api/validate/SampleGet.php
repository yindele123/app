<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 12:01
 */
namespace app\api\validate;

/**
 * Class BannerGet
 * 对获取Banner的接口做验证
 */
class SampleGet extends BaseValidate
{
    protected $rule = [
        'key' => 'number'
    ];

    protected $message = [
//        'location' => 'location 输入'
    ];
}