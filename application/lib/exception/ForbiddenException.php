<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:22
 */

namespace app\lib\exception;

/**
 * token验证失败时抛出此异常
 */
class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}