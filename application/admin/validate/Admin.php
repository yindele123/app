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
        'mobile'=>'require|number|length:11',
        'email'=>'require|email'
    ];
    protected $message  =   [
        'username.require' => '用户名必须填写,用户名长度最多20个字符',
        'username.max'     => '用户名最多不能超过20个字符',
        'password.require'   => '密码不能为空',
        'password.max'     => '密码最多16个字符',
        'mobile.require'  => '手机号码不能为空，必须是11位数字',
        'mobile.number'  => '手机号码必须是数字',
        'mobile.length'  => '手机号码必须是11位数字',
        'email'        => '邮箱格式错误',
    ];
}