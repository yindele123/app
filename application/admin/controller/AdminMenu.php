<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/5 0005
 * Time: 18:52
 */
namespace app\admin\controller;
class AdminMenu extends BaseController{
    public function index(){
        try{
            $menutree=model('AdminMenu')->menutree();
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        return $this->fetch('index',[
            'menutree'=>$menutree
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
                $sonids=model('AdminMenu')->getchilrenid($id);
                $sonids[]=$id;
            }catch (\Exception $e){
                return $this->result('', config('code.error'),$e->getMessage());
            }
            $del=model('AdminMenu')->destroy($sonids);
            if($del){
                return $this->result(['jump_url' => url('adminMenu/index')], config('code.success'), '删除成功');
            }else{
                return $this->result('', config('code.error'), '删除失败');
            }
        }
    }

    public function setController(){
        if(request()->isAjax()){
            $data=input('param.');
            $this->usuallyId($data['id']);
            $controller=explode('/',$data['controller']);
            if(empty($controller) || count($controller) !=2){
                return $this->result('', config('code.error'), '修改操作URL应按照控制器/方法名');
            }
            $update=[
                'controller'=>reset($controller),
                'action'=>end($controller)
            ];
            try{
                $adminMenu=model('AdminMenu')->save($update,['id' => $data['id']]);
            }catch (\Exception $e){
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if($adminMenu) {
                return $this->result(['jump_url' => url('adminMenu/index')], config('code.success'), 'OK');
            } else {
                return $this->result('', config('code.error'), '更新失败');
            }
        }
    }
}