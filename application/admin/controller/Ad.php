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
            'bannerId'=>$param['id'],
            'total'=>$data->total()
        ]);
    }
    public function status(){
        $this->model='bannerItem';
        parent::status();
    }
    public function add(){
        $this->model='bannerItem';
        parent::add();
        $this->model='banner';
        $id=input('param.id');
        $this->usuallyId($id);
        return $this->fetch('info',[
            'action'=>'add',
            'bannerId'=>$id
        ]);
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

    public function del($id = 0){
        if (request()->isAjax()){
            $this->model='bannerItem';
            $banner=$this->usuallyId($id);
            $bannerID=$banner['banner_id'];
            try{
                $del=model('BannerItem')->destroy($id);
            }catch (\Exception $e){
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if($del){
                return $this->result(['jump_url' => url('ad/index',['id'=>$bannerID])], config('code.success'), '删除成功');
            }else{
                return $this->result('', config('code.error'), '删除失败');
            }
        }
    }
}