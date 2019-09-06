<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 15:21
 */
namespace app\admin\controller;

class Cate extends BaseController{
    public function index(){
        try{
            $cateres=model('Cate')->catetree();
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        return $this->fetch('index',[
            'cateres'=>$cateres
        ]);
    }

    public function add(){
        parent::add();
        $id=input('param.id');
        $this->usuallyId($id);
        return $this->fetch('info',[
            'pid'=>$id
        ]);
    }
    public function edit(){
        parent::edit();
        $id=input('param.id');
        $cate=$this->usuallyId($id);
        $cateres=$this->usuallyCate();
        return $this->fetch('edit',[
            'data'=>$cate,
            'cateres'=>$cateres
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
}