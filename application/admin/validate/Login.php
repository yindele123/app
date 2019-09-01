<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/29 0029
 * Time: 16:53
 */
namespace app\admin\validate;

class Login extends BaseValidate{
    protected $rule=[
        'username'=>'require|max:20',
        'password'=>'require|max:16',
        'code'=>'require|length:4'
    ];
    protected $message  =   [
        'username.require' => '用户名必须填写,用户名长度最多20个字符',
        'username.max'     => '用户名最多不能超过20个字符',
        'password.require'   => '密码不能为空',
        'password.max'     => '密码最多16个字符',
        'code.require'  => '验证码不能为空，必须是4位',
        'code.length'  => '验证码必须是4位',
    ];
}