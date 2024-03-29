<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 15:01
 */
namespace app\admin\controller;
use app\common\service\Common;
use app\common\service\IAuth;

class Admin extends BaseController {
    private $obj='';

    /**
     * 用户列表
     */
    public function index(){
        $data = input('param.');
        $search=!empty($data['search']) ? $data['search'] : '';
        $query = http_build_query($data);
        $whereData = setCheckTime($data);
        $request=Common::getPageAndSize($data);
        // 获取表里面的数据
        try{
            $admin = model('Admin')->getAdmin($whereData, ['from'=>$request['from'],'title'=>$search,'size'=>$request['size']]);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        try{

            // 获取满足条件的数据总数 =》 有多少页
            $total = model('Admin')->adminCount($whereData,$search);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        /// 结合总数+size  =》 有多少页
        $pageTotal = ceil($total/$request['size']);//1.1 =>2
        return $this->fetch('', [
            'admin' => $admin,
            'pageTotal' => $pageTotal,
            'curr' => $request['page'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'search' => empty($data['search']) ? '' : $data['search'],
            'total' => $total,
            'size' => $request['size'],
            'query' => $query,
        ]);
    }
    private function authGroup(){
        try{
            $authGroup=model('AuthGroup')->authRroupList();
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('admin/index'),6,3);
        }
        return $authGroup;
    }
    /****
     * 添加用户
     */
    public function add(){
        if(request()->isAjax()){
            $data=input('post.');
            $this->validateCheck($data);
            try {
                $find=model('Admin')->getUser($data['username']);
            }catch (\Exception $e) {
                return $this->result('',config('code.error'),$e->getMessage());

            }
            if($find){
                return $this->result('',config('code.error'),'用户名已存在');
            }
            $data['pwd_key']=getNumberCode(4);
            $data['password']=IAuth::setPassword($data['password'],config('key.password_key'),$data['pwd_key']);

            $this->model='AuthGroup';
            $this->usuallyId($data['authGroup']);
            //开始事物处理
            $adminModel=model('Admin');
            $AuthGroupAccessModel=model('AuthGroupAccess');
            $adminModel->startTrans();
            $AuthGroupAccessModel->startTrans();
            try {
                $user=$adminModel->add($data);
                $authGroupAccess=[
                    'uid'=>$user,
                    'group_id'=>$data['authGroup']
                ];
                $access=$AuthGroupAccessModel->save($authGroupAccess);
            } catch (\Exception $e) {
                //事务回滚
                $adminModel->rollback();
                $AuthGroupAccessModel->rollback();
                return $this->result('',config('code.error'),$e->getMessage());
            }
            if (!empty($user) && !empty($access)) {
                //提交事务处理
                $adminModel->commit();
                $AuthGroupAccessModel->commit();
                return $this->result('',config('code.success'),'添加用户成功');
            } else {
                //事务回滚
                $adminModel->rollback();
                $AuthGroupAccessModel->rollback();
                return $this->result('',config('code.error'),'添加用户失败');
            }

        }
        $authGroup=$this->authGroup();
        return $this->fetch('info',[
            'action'=>'add',
            'authGroup'=>$authGroup
        ]);
    }

    public function edit()
    {
        if (request()->isAjax()){
            $data = input('post.');
            $this->usuallyId($data['id']);
            $this->model='AuthGroup';
            $this->usuallyId($data['authGroup']);
            try {
                $find=model('Admin')->field('username')->where(['username'=>$data['username'],'id'=>['neq',$data['id']]])->find();
            }catch (\Exception $e) {
                return $this->result('',config('code.error'),$e->getMessage());

            }
            if($find){
                return $this->result('',config('code.error'),'用户名已存在');
            }
            $pwd_key=getNumberCode(4);
            if(empty($data['password'])){
                $userUpdate=[
                    'username'=>$data['username']
                ];
            }else{
                $userUpdate=[
                    'username'=>$data['username'],
                    'pwd_key'=>$pwd_key,
                    'password'=>IAuth::setPassword($data['password'],config('key.password_key'),$pwd_key)
                ];
            }
            $authGroupAccessUpdate=['group_id'=>$data['authGroup']];
            $adminModel=model('Admin');
            $AuthGroupAccessModel=model('AuthGroupAccess');
            $AuthGroupAccessModelFind=$AuthGroupAccessModel->where(['uid'=>$data['id']])->find();
            $adminModel->startTrans();
            $AuthGroupAccessModel->startTrans();
            try{
                $userU=$adminModel->allowField(true)->save($userUpdate,['id'=>$data['id']]);
                $accessU=$AuthGroupAccessModelFind['group_id']==$data['authGroup'] ? 1 : $AuthGroupAccessModel->where(array('uid'=>$data['id']))->update($authGroupAccessUpdate);
            }catch (\Exception $e){
                $adminModel->rollback();
                $AuthGroupAccessModel->rollback();
                return $this->result('',config('code.error'),$e->getMessage());
            }
            if (!empty($userU) && !empty($accessU)) {
                //提交事务处理
                $adminModel->commit();
                $AuthGroupAccessModel->commit();
                return $this->result('',config('code.success'),'修改用户成功');
            } else {
                //事务回滚
                $adminModel->rollback();
                $AuthGroupAccessModel->rollback();
                return $this->result('',config('code.error'),'修改用户失败');
            }
        }
        $id=input('param.id');
        try{
            $user=model('Admin')->getAdminGroup($id);
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('Admin/index'),6,3);
        }
        $authGroup=$this->authGroup();
        return $this->fetch('info',[
            'action'=>'edit',
            'authGroup'=>$authGroup,
            'data'=>$user
        ]);
    }
}