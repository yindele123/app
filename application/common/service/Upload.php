<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/11 0011
 * Time: 17:50
 */
namespace app\common\service;
use think\Loader;
Loader::import('qiniu.autoload', EXTEND_PATH, '.php');
//引入鉴权类
use Qiniu\Auth;
//引入上传类
use Qiniu\Storage\UploadManager;

/**
 * 七牛图片基础类库
 * Class Upload
 * @package app\common\lib
 */
class Upload {
    /**
     * 图片上传
     */
    public static function image($model) {
        if(empty($_FILES['file']['tmp_name'])) {
            exception('您提交的图片数据不合法', 404);
        }
        if(empty($model)){
            exception('您提交的图片数据不合法', 404);
        }
        /// 要上传的文件的
        $file = $_FILES['file']['tmp_name'];
        /*$ext = explode('.', $_FILES['file']['name']);
        $ext = $ext[1];*/
        $pathinfo = pathinfo($_FILES['file']['name']);
        //halt($pathinfo);
        $ext = $pathinfo['extension'];

        $config = config('qiniu');
        // 构建一个鉴权对象
        $auth  = new Auth($config['ak'], $config['sk']);
        //生成上传的token
        $token = $auth->uploadToken($config['bucket']);
        // 上传到七牛后保存的文件名
        $key  = 'upload/'.$model.'/'.date('Y')."/".date('m')."/".substr(md5($file), 0, 5).date('YmdHis').rand(0, 9999).'.'.$ext;

        //初始UploadManager类
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $file);

        if($err !== null) {
            return null;
        } else {
            return $key;
        }
    }
}