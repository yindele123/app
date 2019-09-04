<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/1 0001
 * Time: 19:49
 */
namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\common\service\Common;
use app\lib\exception\ErrorException;

class Index extends BaseController{
    /**
     * 获取首页接口
     * 1、头图  4-6
     * 2、推荐位列表 默认4条
     */
    public function index() {
        try{
            $heads = model('News')->getIndexHeadNormalNews();
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
        $heads = $this->getDealNews($heads);
        try{
            $positions = model('News')->getPositionNormalNews();
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
        $positions = $this->getDealNews($positions);

        $result = [
            'heads' => $heads,
            'positions' => $positions,
        ];


        return show(config('code.success'), 'OK', $result, 200);
    }

    /**
     * 客户端初始化接口
     * 1、检测APP是否需要升级
     */
    public function init() {
        try{
            $version = model('Version')->getLastNormalVersionByAppType($this->headers['apptype']);
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
        if(empty($version)) {
            throw new ErrorException(['msg'=>'error','code'=>404]);
        }

        if($version->version > $this->headers['version']) {
            $version->is_update = $version->is_force == 1 ? config('app.force_update') : config('app.be_update');
        }else {
            $version->is_update = config('app.no_update');  // 0 不更新 ， 1需要更新, 2强制更新
        }

        // 记录用户的基本信息 用于统计
        $actives = [
            'version' => $this->headers['version'],
            'app_type' => $this->headers['apptype'],
            'did' => $this->headers['did'],
        ];
        try {
            model('AppActive')->add($actives);
        }catch (\Exception $e) {
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
        return show(config('code.success'), 'OK', $version, 200);
    }

}