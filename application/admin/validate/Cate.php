<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 16:45
 */
namespace app\admin\validate;

class Cate extends BaseValidate{
    protected $rule=[
        'name'=>'require'
    ];
    protected $message  =   [
        'name.require' => '分类名称不能为空'
    ];
}