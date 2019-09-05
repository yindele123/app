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

        $str = 'BX6oMrM9sPyYUeJJx6+RK3twvHxknHAs0r08wVGon2+McLE9qIV3jIOv3EtC0Qg5yyLWLshZUA+l2mzbxzQSPQ==';
        // col9j6cqegAKiiey3IrXWo2zCRGHw8vogniwQZab0fgIVnKDb7Rin03dOqY2qLWP
        //echo IAuth::setSign($data);exit;
        return show(1,'OK');
    }
}