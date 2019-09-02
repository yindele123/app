<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 16:20
 */

namespace app\lib\exception;

/**
 * Class ParameterException
 * 通用参数类异常错误
 */
class ErrorException extends BaseException
{
    public $code = 400;
    public $errorCode = 10000;
    public $msg = "error";
}