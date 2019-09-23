<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:25
 */
namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}