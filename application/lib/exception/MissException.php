<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 17:58
 */
namespace app\lib\exception;
/**
 * 404时抛出此异常
 */
class MissException extends BaseException {
    public $code = 404;
    public $msg = 'global:your required resource are not found';
    public $errorCode = 10001;
}