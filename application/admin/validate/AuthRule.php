<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 20:36
 */
namespace app\admin\validate;
class AuthRule extends BaseValidate{
    protected $rule=[
        'title'=>'require',
        'name'=>'isFormat'
    ];
    protected $message  =   [
        'title.require' => '规则中文名称不能为空',
        'name' => '请输入格式：控制器/操作名'
    ];

    protected function isFormat($value, $rule='', $data='', $field='')
    {
        if(!empty($value)){
            $controller=explode('/',$value);
            if(empty($controller) || count($controller) !=2){
                return false;
            }
        }
        return true;
    }
}