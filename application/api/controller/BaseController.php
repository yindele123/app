<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/1 0001
 * Time: 19:49
 */
namespace app\api\controller;
use app\common\service\IAuth;
use app\lib\exception\ParameterException;
use think\Cache;
use think\Controller;

class BaseController extends Controller{
    public $headers='';
    /**
     * 初始化的方法
     */
    public function _initialize() {
        $this->checkRequestAuth();
        //$this->testAes();
    }
    /**
     * 检查每次app请求的数据是否合法
     */
    public function checkRequestAuth() {
        // 获取headers
        $headers = request()->header();
        // 基础参数校验
        if(empty($headers['sign'])) {
            throw new ParameterException([
                'msg' => 'sign不存在',
                'errorCode' => 40000
            ]);
        }
        if(!in_array($headers['apptype'], config('app.apptypes'))) {
            throw new ParameterException([
                'msg' => 'app_type不合法',
                'errorCode' => 40001
            ]);
        }
        // 需要sign
        if(!IAuth::checkSignPass($headers)) {
            throw new ParameterException([
                'msg' => '授权码sign失败',
                'errorCode' => 40002,
                'code'=>401
            ]);
        }
        Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));

        $this->headers = $headers;
    }
    /**
     * 获取处理的新闻的内容数据
     * @param array $news
     * @return array
     */
    protected  function getDealNews($news = []) {
        if(empty($news)) {
            return [];
        }
        $cats = config('cat.lists');
        foreach($news as $key => $new) {
            $news[$key]['catname'] = $cats[$new['catid']] ? $cats[$new['catid']] : '-';
        }
        return $news;
    }

}