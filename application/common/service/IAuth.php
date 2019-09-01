<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/29 0029
 * Time: 15:50
 */
namespace app\common\service;
use app\common\service\Aes;
class IAuth{
    /**
     * 设置密码
     * @param string $password 需要加密的密码
     * @param string $key 加密KEY
     * @param string $rand 加密的随机数
     * @return string
     */
    public static function setPassword($password,$key,$rand) {
        return md5($password.$key.$rand);
    }

    /**
     * 生成每次请求的sign
     * @param array $data
     * @return string
     */
    public static function setSign($data = []) {
        // 1 按字段排序
        ksort($data);
        // 2拼接字符串数据  &
        $string = http_build_query($data);
        // 3通过aes来加密
        $string = (new Aes())->encrypt($string);

        return $string;
    }

    /**
     * 检查sign是否正常
     * @param array $data
     * @param $data
     * @return boolen
     */
    public static function checkSignPass($data) {
        $str = (new Aes())->decrypt($data['sign']);

        if(empty($str)) {
            return false;
        }

        // did=xx&app_type=3
        parse_str($str, $arr);
        if(!is_array($arr) || empty($arr['did'])
            || $arr['did'] != $data['did']
        ) {
            return false;
        }
        if(!config('app_debug')) {
            if ((time() - ceil($arr['time'] / 1000)) > config('app.app_sign_time')) {
                return false;
            }
            //echo Cache::get($data['sign']);exit;
            // 唯一性判定
            if (Cache::get($data['sign'])) {
                return false;
            }
        }
        return true;
    }

}