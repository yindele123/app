<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/30 0030
 * Time: 17:05
 */

namespace app\admin\controller;

use app\common\service\Aes;
use app\common\service\IAuth;
use think\Controller;
use think\Request;
use app\lib\Auth;
use app\common\service\Upload as UploadService;

class BaseController extends Controller
{
    public $validate = '';
    public $user = '';
    public $model = '';

    public function __construct(Request $request = null)
    {
        $this->user = session(config('admin.session_user'), '', config('admin.session_user_scope'));
        parent::__construct($request);
    }

    public function _initialize()
    {
        // 判定用户是否登录
        $isLogin = $this->isLogin($this->user);
        if(!$isLogin) {
            return $this->redirect('login/index');
        }
        $this->checkLogin();
        $auth = new Auth();
        $request = Request::instance();
        $con = $request->controller();
        $action = $request->action();
        $name = $con . '/' . $action;
        $notCheck = array('Index/index','Index/welcome');
        if($this->user->id!=1) {
            if (!in_array($name, $notCheck)) {
                if (!$auth->check($name, $this->user->id)) {
                    if (request()->isAjax()) {
                        return $this->result('', config('code.error'), '没有权限');
                    }
                    $this->error('没有权限', url('index/index'));
                }
            }
        }
    }

    /**
     * 检查用户只能再一端登陆
     * @return bool|void
     */
    protected function checkLogin(){
        $user=model('Admin')->getUser($this->user['username']);
        $sessionP=(new Aes())->decrypt($this->user['password']);
        $sessionK=(new Aes())->decrypt($this->user['pwd_key']);
        if(IAuth::setPassword($sessionP,config('key.password_key'),$sessionK) !== $user['password']){
            session(null, config('admin.session_user_scope'));
            return $this->redirect('login/index');
        }
        return true;
    }

    /**
     * 判定是否登录
     * @return bool
     */
    public function isLogin($user)
    {
        if ($user && $user['id']) {
            return true;
        }

        return false;
    }

    /**
     * @param $data 需要验证的内容
     */
    protected function validateCheck($data)
    {
        $validate = $this->validate ? $this->validate : request()->controller();
        $validateC = validate($validate);
        if (!$validateC->check($data)) {
            return $this->result('', config('code.error'), is_array($validateC->getError()) ? implode(';', $validateC->getError()) : $validateC->getError());
        }
    }


    /**
     * 删除逻辑
     */
    public function delete($id = 0)
    {
        if (request()->isAjax()) {
            if (!intval($id)) {
                return $this->result('', config('code.error'), 'ID不合法');
            }

            $model = $this->model ? $this->model : request()->controller();
            $this->usuallyId($id);
            try {
                $res = model($model)->save(['status' => config('code.status_delete')], ['id' => $id]);
            } catch (\Exception $e) {
                return $this->result('', config('code.error'), $e->getMessage());
            }

            if ($res) {
                return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
            }
            return $this->result('', config('code.error'), '删除失败');
        }

    }

    /**
     * 通用修改改状态
     */
    public function status()
    {
        if (request()->isAjax()) {
            $data = input('param.');
            $model = $this->model ? $this->model : request()->controller();
            $this->usuallyId($data['id']);
            try {
                $res = model($model)->save(['status' => $data['status']], ['id' => $data['id']]);
            } catch (\Exception $e) {
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if ($res) {
                return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
            }
            return $this->result('', config('code.error'), '修改失败');
        }
    }

    /**
     * 通用修改排序
     */
    public function sort()
    {
        if (request()->isAjax()) {
            $data = input('param.');
            $model = $this->model ? $this->model : request()->controller();
            $this->usuallyId($data['id']);
            try {
                $res = model($model)->save(['sort' => $data['sort']], ['id' => $data['id']]);
            } catch (\Exception $e) {
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if ($res) {
                return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], config('code.success'), 'OK');
            }
            return $this->result('', config('code.error'), '修改失败');
        }
    }


    /**
     * 通用添加
     * @return mixed|void
     */
    public function add()
    {
        if (request()->isAjax()) {
            $model = $this->model ? $this->model : request()->controller();
            $data = $this->_setArray(input('post.'));
            $this->validateCheck($data);
            if ($model == 'Version') {
                try {
                    $appType = model($model)->where(['app_type' => $data['app_type'], 'status' => 1])->field('version')->order('id desc')->find();
                } catch (\Exception $e) {
                    return $this->result('', config('code.error'), $e->getMessage());
                }
                if ($appType->version >= $data['version']) {
                    return $this->result('', config('code.error'), '发布的版本必须要比上个版本高,上个版本是：' . $appType->version);
                }
            }
            //入库操作
            try {
                $id = model($model)->add($data);
            } catch (\Exception $e) {
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if ($id) {
                return $this->result(['jump_url' => url('' . $model . '/index')], config('code.success'), 'OK');
            } else {
                return $this->result('', config('code.error'), '新增失败');
            }
        }
    }

    /**
     * 通用获取ID判断
     * @return mixed|void
     */
    public function usuallyId($id)
    {
        if (request()->action() == 'usuallyId') {
            return $this->alert('请不要非法操作', url('index/index'), 6, 3);
        }
        $model = $this->model ? $this->model : request()->controller();
        try {
            $cate = model($model)::get($id);
        } catch (\Exception $e) {
            return $this->result('', config('code.error'), $e->getMessage());
        }
        if (empty($cate)) {
            if (request()->isAjax()) {
                return $this->result('', config('code.error'), '请不要非法操作');
            } else {
                $this->error('请不要非法操作');
            }

        }
        return $cate;
    }

    /**
     * 通用获取分类
     * @return mixed|void
     */
    public function usuallyCate($field = [])
    {
        if (request()->action() == 'usuallyCate') {
            return $this->alert('请不要非法操作', url('index/index'), 6, 3);
        }
        $model = $this->model ? $this->model : request()->controller();
        try {
            $field = empty($field) ? ['id', 'name', 'pid', 'sort', 'status'] : $field;
            $cateres = model($model)->getCateList($field);
        } catch (\Exception $e) {
            if (request()->isAjax()) {
                return $this->result('', config('code.error'), $e->getMessage());
            } else {
                $this->error($e->getMessage());
            }
        }
        return $cateres;
    }

    /**
     * 通用修改
     */
    public function edit()
    {
        if (request()->isAjax()) {
            $model = $this->model ? $this->model : request()->controller();
            $data = $this->_setArray(input('post.'));
            $this->validateCheck($data);
            //入库操作
            try {
                $save = model($model)->allowField(true)->save($data, ['id' => $data['id']]);
            } catch (\Exception $e) {
                return $this->result('', config('code.error'), $e->getMessage());
            }
            if ($save) {
                return $this->result(['jump_url' => url('' . $model . '/index')], config('code.success'), 'OK');
            } else {
                return $this->result('', config('code.error'), '更新失败');
            }
        }
    }

    private function _setArray($data){
        foreach($data as $key=>$val){
            if(is_array($val)){
                $data[$key]=implode(',', $val);
            }
        }
        return $data;
    }

    function alert($msg = '', $url = '', $icon = '', $time = 3)
    {
        $str = '<script type="text/javascript" src="' . config('admin.admin_static') . 'js/jquery.min.js"></script><script type="text/javascript" src="' . config('admin.common') . 'lib/layui/layui.js"></script><script type="text/javascript" src="' . config('admin.admin_static') . '/js/xadmin.js"></script>';
        $str .= '<script>$(function(){layer.msg("' . $msg . '",{icon:' . $icon . ',time:' . ($time * 1000) . '});setTimeout(function(){self.location.href="' . $url . '"},2000)});</script>';//主要方法
        return $str;
    }
    //单个图片上传
    public function single($model){
        try {
            $image = UploadService::image($model);
        }catch (\Exception $e) {
            return json([
                'code'=>config('code.error'),
                'msg'=>$e->getMessage()
            ]);
        }
        if($image) {
            $data=[
                'code'=>config('code.success'),
                "msg"=>config('qiniu.image_url').'/'.$image
            ];
            return json($data);
        }else {
            return json([
                'code'=>config('code.error'),
                'msg'=>'上传失败'
            ]);
        }

    }
}