<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 17:50
 */
namespace app\api\validate;
class IDMustBePositiveInt extends BaseValidate{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];
}