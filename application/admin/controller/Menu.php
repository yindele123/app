<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 15:07
 */
namespace app\admin\controller;

class Menu extends BaseController{

    public function index(){
        try{
            $menus=model('Menu')->menutree();
        }catch (\Exception $e){
            return $this->alert('请不要非法操作',url('menu/index'),6,3);
        }
        return $this->fetch('index',[
            'menu'=>$menus
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
        return $this->fetch('edit',[
            'data'=>$cate
        ]);
    }

    public function del(){
        if(request()->isAjax()){
            $id=input('param.id',0,'intval');
            try{
                $cate=model('menu')::get($id);
                if (empty($cate)){
                    return $this->result('', config('code.error'), '请不要非法操作');
                }
            }catch (\Exception $e){
                return $this->result('', config('code.error'),$e->getMessage());
            }
            try{
                $sonids=model('menu')->getchilmenu($id);
                $sonids[]=$id;
            }catch (\Exception $e){
                return $this->result('', config('code.error'),$e->getMessage());
            }
            $list=[];
            foreach($sonids as $k=>$v){
                $list[$k]['id']=$v;
                $list[$k]['status']=-1;
            }
            $del=model('menu')->saveAll($list);
            if($del){
                return $this->result(['jump_url' => url('menu/index')], config('code.success'), '删除成功');
            }else{
                return $this->result('', config('code.error'), '删除失败');
            }
        }
    }
}