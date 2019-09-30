<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/25 0025
 * Time: 11:20
 */
namespace app\admin\controller;
use app\admin\model\Banner as BannerModel;
use app\common\service\Common;

class Banner extends BaseController{
    //广告分类列表
    public function index(){
        $data = input('param.');
        $query = http_build_query($data);
        $key=!empty($data['key']) ? $data['key'] : '';
        $request=Common::getPageAndSize($data);
        try{
            $banner=BannerModel::getBanners($key,$request['page'],$request['size']);
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('banner/index'),6,3);
        }
        return $this->fetch('index',[
            'banner'=>$banner,
            'total' => $banner->total(),
            'size' => $request['size'],
            'curr' => $request['page'],
            'key'=>$key,
            'query' => $query
        ]);
    }
    //添加广告分类列表
    public function add()
    {
        parent::add();
        return $this->fetch('info',[
            'action'=>'add'
        ]);
    }
    //修改广告分类列表
    public function edit()
    {
        parent::edit();
        $id=input('param.id');
        $banner=$this->usuallyId($id);
        return $this->fetch('info',[
            'data'=>$banner,
            'action'=>'edit'
        ]);
    }
    //删除广告分类列表
    public function del(){
        if(request()->isAjax()){
            $data = input('param.');
            $banner=new BannerModel();
            $result=$banner->getBannerById($data['id']);
            if(empty($result)){
                return $this->result('', config('code.error'), '请不要非法操作');
            }
            if($result['item_count']!=0){
                return $this->result('', config('code.error'), '改广告分类下有广告不能删除，请先删除广告');
            }
            $res=$banner::destroy($data['id']);
            if ($res) {
                return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
            }
            return $this->result('', config('code.error'), '删除失败');
        }
        return $this->alert('请不要非法操作',url('banner/index'),6,3);
    }
    //广告列表
    public function ad(){
        $id=input('param.id');
        $this->usuallyId($id);
        return $this->fetch('ad',[

        ]);
    }
}