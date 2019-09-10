<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/29 0029
 * Time: 16:18
 */
namespace app\admin\controller;
use app\common\service\IAuth;

class Login extends BaseController {
    public function _initialize() {
    }
    public function index()
    {
        $isLogin = $this->isLogin($this->user);
        if($isLogin) {
            return $this->redirect('index/index');
        }else {
            // 如果后台用户已经登录了， 那么我们需要跳到后台页面
            return $this->fetch();
        }
    }
    public function check(){
        if(request()->isAjax()){
            $data=input('post.');
            $this->validateCheck($data);
            try{
                $user=model('Admin')->getUser($data['username']);
            }catch (\Exception $e){
                return $this->result('',config('code.error'),$e->getMessage());
            }
            if(empty($user['username']) || $user['status'] !=config('code.status_normal')){
                return $this->result('',config('code.error'),'该用户不存在或给禁用了，请联系管理员');
            }
            if($user['password'] != IAuth::setPassword($data['password'],config('key.password_key'),$user['pwd_key'])){
                return $this->result('',config('code.error'),'密码错误');
            }
            if (!captcha_check($data['code'])) {
                return $this->result('',config('code.error'),'验证码不正确');
            }
            $pwd_key=getNumberCode(4);
            $update=[
                'pwd_key'=>$pwd_key,
                'password'=>IAuth::setPassword($data['password'],config('key.password_key'),$pwd_key),
                'last_login_ip'=>request()->ip(),
                'last_login_time'=>time()
            ];
            try{
                model('Admin')->save($update,['id'=>$user['id']]);
            }catch (\Exception $e){
                return $this->result('',config('code.error'),$e->getMessage());
            }
            unset($user['pwd_key']);
            session(config('admin.session_user'), $user, config('admin.session_user_scope'));
            return $this->result(['jump_url' => url("index/index")],config('code.success'),'登陆成功');
        }
        return false;
    }

    /**
     * 退出登录的逻辑
     * 1、清空session
     * 2、 跳转到登录页面
     */
    public function logout() {
        session(null, config('admin.session_user_scope'));
        // 跳转
        $this->redirect('login/index');
    }
}