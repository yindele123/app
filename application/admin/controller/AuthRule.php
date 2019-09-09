<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/5 0005
 * Time: 18:52
 */
namespace app\admin\controller;
class AuthRule extends BaseController{
    private $field=['id', 'title', 'pid', 'sort', 'status'];
    public function index(){
        try{
            $authRule=model('AuthRule')->ruleTree();
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        $menu=$this->usuallyCate($this->field);
        return $this->fetch('index',[
            'authRule'=>$authRule,
            'menu'=>$menu
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
        $menu=$this->usuallyCate($this->field);
        return $this->fetch('edit',[
            'data'=>$cate,
            'menu'=>$menu
        ]);
    }

    public function del(){
        if(request()->isAjax()){
            $id=input('param.id');
            $this->usuallyId($id);
            try{
                $sonids=model('AuthRule')->getchilrenid($id);
                $sonids[]=$id;
            }catch (\Exception $e){
                return $this->result('', config('code.error'),$e->getMessage());
            }
            $del=model('AuthRule')->destroy($sonids);
            if($del){
                return $this->result(['jump_url' => url('authRule/index')], config('code.success'), '删除成功');
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
                'name'=>$data['controller']
            ];
            try{
                $authRule=model('AuthRule')->save($update,['id' => $data['id']]);
            }catch (\Exception $e){
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if($authRule) {
                return $this->result(['jump_url' => url('authRule/index')], config('code.success'), 'OK');
            } else {
                return $this->result('', config('code.error'), '更新失败');
            }
        }
    }
}