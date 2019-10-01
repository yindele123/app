<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/30 0030
 * Time: 15:40
 */
namespace app\admin\validate;

class Ad extends BaseValidate{
    protected $rule=[
        'title'=>'require',
        'start_time'=>'require',
        'end_time'=>'require|gt:start_time',
        'url'=>'require',
        'image'=>'require'
    ];
    protected $message  =   [
        'title.require' => '广告标题不能为空',
        'start_time.require' => '开始日不能为空',
        'end_time.require' => '截止日不能为空',
        'end_time.gt' => '截止日要大于开始日',
        'url.require' => 'URL不能为空',
        'image.require' => '缩略图不能为空'
    ];
}