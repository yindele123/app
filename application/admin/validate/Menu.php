<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/5 0005
 * Time: 17:02
 */
namespace app\admin\validate;

class Menu extends BaseValidate{
    protected $rule=[
        'name'=>'require'
    ];
    protected $message  =   [
        'name.require' => '菜单名称不能为空'
    ];
}