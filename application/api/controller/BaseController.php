<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/1 0001
 * Time: 19:49
 */
namespace app\api\controller;
use app\common\service\Common;
use app\common\service\IAuth;
use app\lib\exception\ErrorException;
use app\lib\exception\ParameterException;
use think\Cache;
use think\Controller;
use app\api\service\Token;

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
        if(!in_array($headers['apptype'], apptype())) {
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
     * 通用获取ID判断
     * @return mixed|void
     */
    protected function usuallyId($id,$model='',$msg='请不要非法操作',$cache='usuallyId',$cacheTime=500)
    {
        if(empty($id) || empty($model)){
            return false;
        }
        $cate = Cache::get($cache.$id);
        if (empty($cate)) {
            try {
                $cate = model($model)::get($id);
            } catch (\Exception $e) {
                Common::setLog(request()->url() . '-----' . $e->getMessage());
                throw new ErrorException();
            }
            if (empty($cate)) {
                throw new ErrorException(['msg' => $msg]);
            }
            Cache::set($cache.$id, $cate, $cacheTime);
        }
        return $cate;
    }

    protected function checkExclusiveScope()
    {
        Token::needExclusiveScope();
    }

    protected function checkPrimaryScope()
    {
        Token::needPrimaryScope();
    }

    protected function checkSuperScope()
    {
        Token::needSuperScope();
    }
}