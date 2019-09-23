<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/1 0001
 * Time: 19:56
 */
namespace app\api\controller;
use app\common\service\Aes;
use app\common\service\IAuth;
use app\common\service\Time;

class Test extends BaseController {
    public function index(){
        $data = [
            'did' => 'https://www.baidu.com',
            'version' => 1,
            'time' => Time::get13TimeStamp(),
        ];

        //$str = 'KENc+2JKmziatyUS0q44480vT+LbyBhaOkix6XwNffDlm7vRThMekGOiYj5AWvOJzRM9sYMdru7+qkqjg28biw==';
        //$str1='6AOnrgXN1GPDMup3gHneFt0xuvJ6BqgUfnJMakZ7+++3wthi38xlCxDxhp69H6yz1OyhBho6sBjUkbeJ1QGr/Q==';
        // col9j6cqegAKiiey3IrXWo2zCRGHw8vogniwQZab0fgIVnKDb7Rin03dOqY2qLWP
        //echo IAuth::setSign($data);exit;

        //echo (new Aes())->decrypt($str1);exit;
        return show(1,'OK');
    }
}