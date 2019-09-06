<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 20:36
 */
namespace app\admin\validate;
class AdminMenu extends BaseValidate{
    protected $rule=[
        'name'=>'require',
        'controller'=>'require',
        'action'=>'require'
    ];
    protected $message  =   [
        'name.require' => '菜单名不能为空',
        'controller.require' => '控制器不能为空',
        'action.require' => '操作名不能为空'
    ];
}