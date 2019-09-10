<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 16:23
 */
namespace app\admin\validate;

class Admin extends BaseValidate{
    protected $rule=[
        'username'=>'require|max:20',
        'password'=>'require|max:16',
        'repass'=>'require|confirm:password'
    ];
    protected $message  =   [
        'username.require' => '用户名必须填写,用户名长度最多20个字符',
        'username.max'     => '用户名最多不能超过20个字符',
        'password.require'   => '密码不能为空',
        'password.max'     => '密码最多16个字符'
    ];
}