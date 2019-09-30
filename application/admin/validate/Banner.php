<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/30 0030
 * Time: 14:34
 */

namespace app\admin\validate;

class Banner extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'identification' => 'require'
    ];
    protected $message = [
        'name.require' => '广告分类名称不能为空',
        'identification.require' => '标识不能为空'
    ];
}