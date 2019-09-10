<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/9 0009
 * Time: 17:28
 */
namespace app\admin\controller;
use app\lib\PHPTree;
class AuthGroup extends BaseController{
    public function index(){
        $authRroupList=model('AuthGroup')->authRroupList();
        return $this->fetch('',[
            'authRroupList'=>$authRroupList
        ]);
    }

    private function ruleTree(){
        try{
            $authRuleRes=model('AuthRule')->ruleTree('',true);
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('authGroup/index'),6,3);
        }
        return $authRuleRes;
    }

    public function add()
    {
        parent::add();
        $authRuleRes=$this->ruleTree();
        return $this->fetch('info',[
            'authRuleRes'=>(new PHPTree())::makeTree($authRuleRes),
            'action'=>'add'
        ]);
    }
    public function edit()
    {
        parent::edit();
        $authRuleRes=$this->ruleTree();
        $id=input('param.id');
        $data=$this->usuallyId($id);
        $data['rules']=empty($data['rules']) ? [] : explode(',',$data['rules']);
        return $this->fetch('info',[
            'authRuleRes'=>(new PHPTree())::makeTree($authRuleRes),
            'action'=>'edit',
            'data'=>$data
        ]);
    }

    public function del($id = 0){
        if (request()->isAjax()){
            $this->usuallyId($id);
            try{
                $groupId=model('AuthGroupAccess')->where(['group_id'=>$id])->find();
            }catch (\Exception $e){
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if(!empty($groupId)){
                return $this->result('', config('code.error'), '该角色下面有用户不能删除');
            }
            try{
                $del=model('AuthGroup')->destroy($id);
            }catch (\Exception $e){
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if($del){
                return $this->result(['jump_url' => url('authGroup/index')], config('code.success'), '删除成功');
            }else{
                return $this->result('', config('code.error'), '删除失败');
            }
        }
    }
}