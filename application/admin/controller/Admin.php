<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 15:01
 */
namespace app\admin\controller;
use app\common\service\IAuth;

class Admin extends BaseController {
    private $obj='';

    /**
     * 用户列表
     */
    public function index(){
        return view();
    }
    /****
     * 添加用户
     */
    public function add(){
        if(request()->isAjax()){
            $data=input('post.');
            $this->validateCheck($data);
            try {
                $find=model('AdminUser')->getUser($data['username']);
            }catch (\Exception $e) {
                return $this->result('',config('code.error'),$e->getMessage());

            }
            if($find){
                return $this->result('',config('code.error'),'用户名已存在');
            }
            try{
                $data['status']=1;
                $data['pwd_key']=getNumberCode(4);
                $data['password']=IAuth::setPassword($data['password'],config('key.password_key'),$data['pwd_key']);
                $user=model('AdminUser')->add($data);
            }catch (\Exception $e){
                return $this->result('',config('code.error'),$e->getMessage());
            }
            if($user){
                return $this->result(['jump_url' => url("admin/index")],config('code.success'),'添加用户成功');
            }else{
                return $this->result('',config('code.error'),'添加用户失败');
            }
        }
        return view();
    }
}