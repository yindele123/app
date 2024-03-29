<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 15:21
 */
namespace app\admin\controller;

use app\common\service\Common;

class Cate extends BaseController{
    public function index(){
        $data = input('param.');
        $typeValue=empty($data['type']) ? '' : $data['type'];
        try{
            $cateres=model('Cate')->catetree(['type'=>$typeValue]);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        return $this->fetch('index',[
            'cateres'=>$cateres,
            'menu'=>Common::getMenu(32),
            'type_value'=>$typeValue
        ]);
    }

    public function add(){
        parent::add();
        $param=input('param.');
        if(!empty($param['id']))$this->usuallyId($param['id']);
        return $this->fetch('info',[
            'pid'=>empty($param['id']) ? '' : $param['id'],
            'menu'=>Common::getMenu(32)
        ]);
    }
    public function edit(){
        parent::edit();
        $param=input('param.');
        $cate=$this->usuallyId($param['id']);
        $type=empty($param['type']) ? '' : $param['type'];
        $cateres=$this->usuallyCate(['type'=>$type]);
        return $this->fetch('edit',[
            'data'=>$cate,
            'cateres'=>$cateres,
            'menu'=>Common::getMenu(32)
        ]);
    }
    public function del(){
        if(request()->isAjax()){
            $id=input('param.id');
            $this->usuallyId($id);
            try{
                $sonids=model('Cate')->getchilrenid($id);
                $sonids[]=$id;
            }catch (\Exception $e){
                return $this->result('', config('code.error'),$e->getMessage());
            }
            try{
                $find=model('News')->where(['catid'=>['in',$sonids]])->find();
            }catch (\Exception $e){
                return $this->result('', config('code.error'),$e->getMessage());
            }
            if(!empty($find)){
                return $this->result('', config('code.error'),'分类下面有文章，不能删除');
            }
            $del=model('Cate')->destroy($sonids);
            if($del){
                return $this->result(['jump_url' => url('cate/index')], config('code.success'), '删除成功');
            }else{
                return $this->result('', config('code.error'), '删除失败');
            }
        }
    }
    public function images(){
        $data=input('param.');
        $this->usuallyId($data['id']);
        $request=Common::getPageAndSize($data);
        $query = http_build_query($data);
        try{
            $result=model('CateAttach')->getCateAttachS();
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('cate/index'),6,3);
        }
        return $this->fetch('images',[
            'data' => $result,
            'curr' => $request['page'],
            'total' => $result->total(),
            'size' => $request['size'],
            'query' => $query
        ]);
    }
}