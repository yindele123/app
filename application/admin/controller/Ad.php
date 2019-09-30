<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/30 0030
 * Time: 15:29
 */
namespace app\admin\controller;
use app\admin\model\BannerItem;
use app\common\service\Common;
class Ad extends BaseController{
//广告列表
    public function index(){
        $param = input('param.');
        $query = http_build_query($param);
        $request=Common::getPageAndSize($param);
        $this->model='banner';
        $this->usuallyId($param['id']);
        $ad=new BannerItem();
        $data=$ad->getAdS($param['id']);
        return $this->fetch('index',[
            'data'=>$data,
            'query'=>$query,
            'size' => $request['size'],
            'curr' => $request['page'],
            'total'=>$data->total()
        ]);
    }
    public function status(){
        $this->model='bannerItem';
        parent::status();
    }
    public function edit(){
        $this->model='bannerItem';
        parent::edit();
        $id=input('param.id');
        $ad=$this->usuallyId($id);
        return $this->fetch('info',[
            'data'=>$ad,
            'action'=>'edit'
        ]);
    }
}