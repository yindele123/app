<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 15:07
 */
namespace app\admin\controller;
use app\common\service\Common;

class Version extends BaseController{

    public function index(){
        $data = input('param.');
        $query = http_build_query($data);
        $request=Common::getPageAndSize($data);
        $whereData = [];
        if(!empty($data['app_type'])){
            $whereData['app_type']=$data['app_type'];
        }
        try{
            $version=model('Version')->getVersionByCondition($whereData,$request['from'], $request['size']);
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('version/index'),6,3);
        }
        try{
            $total = model('Version')->getCountByCondition($whereData);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        $pageTotal = ceil($total/$request['size']);
        //dump(Common::getMenu(12));
        return $this->fetch('',[
            'version'=>$version,
            'query'=> $query,
            'pageTotal' => $pageTotal,
            'curr' => $request['page'],
            'total' => $total,
            'size' => $request['size'],
            'apptype'=>Common::getMenu(1),
            'app_type_value'=>empty($data['app_type']) ? '' : $data['app_type']
        ]);
    }
    public function force(){
        if(request()->isAjax()){
            $data  = input('param.');
            $this->usuallyId($data['id']);
            try {
                $res = model('Version')->save(['is_force' => $data['is_force']], ['id' => $data['id']]);
            }catch(\Exception $e) {
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if($res) {
                return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
            }
            return $this->result('', config('code.error'), '修改失败');
        }
        return false;
    }
    public function add(){
        parent::add();
        return $this->fetch('info',[
            'app_type'=>Common::getMenu(1),
            'is_force'=>Common::getMenu(12),
            'status'=>Common::getMenu(15)
        ]);
    }

    public function edit(){
        parent::edit();
        $id=input('param.id');
        $version=$this->usuallyId($id);
        return $this->fetch('info',[
            'data'=>$version,
            'app_type'=>Common::getMenu(1),
            'is_force'=>Common::getMenu(12),
            'status'=>Common::getMenu(15)
        ]);
    }
}