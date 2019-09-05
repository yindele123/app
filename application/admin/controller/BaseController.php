<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/30 0030
 * Time: 17:05
 */
namespace app\admin\controller;
use think\Controller;
use think\Request;

class BaseController extends Controller{
    public $validate='';
    public $user='';
    public $model='';

    public function __construct(Request $request = null)
    {
        $this->user=session(config('admin.session_user'), '', config('admin.session_user_scope'));
        parent::__construct($request);
    }

    public function _initialize() {
        // 判定用户是否登录
        $isLogin = $this->isLogin($this->user);
        if(!$isLogin) {
            return $this->redirect('login/index');
        }

        //$this->checkLogin();

    }

    /*protected function checkLogin(){
        $user=model('AdminUser')->getUser($this->user['username']);
        if($this->user['password'] !== $user['password']){

        }
    }*/

    /**
     * 判定是否登录
     * @return bool
     */
    public function isLogin($user) {
        if($user && $user['id']) {
            return true;
        }

        return false;
    }

    /**
     * @param $data 需要验证的内容
     */
    protected function validateCheck($data){
        $validate = $this->validate ? $this->validate : request()->controller();
        $validateC=validate($validate);
        if (!$validateC->check($data)) {
            return $this->result('',config('code.error'),is_array($validateC->getError()) ? implode(';', $validateC->getError()) : $validateC->getError());
        }
    }


    /**
     * 删除逻辑
     */
    public function delete($id = 0) {
        if(!intval($id)) {
            return $this->result('', config('code.error'), 'ID不合法');
        }

        $model = $this->model ? $this->model : request()->controller();

        try{
            $getFind=model($model)->get($id);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        if(empty($getFind)){
            return $this->result('', config('code.error'), '请不要非法操作');
        }
        try {
            $res = model($model)->save(['status' => config('code.status_delete')], ['id' => $id]);
        }catch(\Exception $e) {
            return $this->result('', config('code.error'), $e->getMessage());
        }

        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
        }
        return $this->result('', config('code.error'), '删除失败');

    }

    /**
     * 通用修改改状态
     */
    public function status() {
        $data  = input('param.');
        $model = $this->model ? $this->model : request()->controller();
        try{
            $getFind=model($model)->get($data['id']);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        if(empty($getFind)){
            return $this->result('', config('code.error'), '请不要非法操作');
        }
        try {
            $res = model($model)->save(['status' => $data['status']], ['id' => $data['id']]);
        }catch(\Exception $e) {
            return $this->result('', config('code.error'), $e->getMessage());
        }
        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
        }
        return $this->result('', config('code.error'), '修改失败');
    }

    /**
     * 通用修改排序
     */
    public function sort() {
        $data  = input('param.');
        $model = $this->model ? $this->model : request()->controller();
        try{
            $getFind=model($model)->get($data['id']);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        if(empty($getFind)){
            return $this->result('', config('code.error'), '请不要非法操作');
        }
        try {
            $res = model($model)->save(['sort' => $data['sort']], ['id' => $data['id']]);
        }catch(\Exception $e) {
            return $this->result('', config('code.error'), $e->getMessage());
        }
        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
        }
        return $this->result('', config('code.error'), '修改失败');
    }
}