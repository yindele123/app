<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/1 0001
 * Time: 19:53
 */
namespace app\common\service;
class Aes {

    private $key = null;
    private $iv = null;

    /**
     *
     * @param $key 		密钥
     * @param $iv 		加密解密初始向量
     * @return String
     */
    public function __construct() {
        // 需要小伙伴在配置文件app.php中定义aeskey
        $this->key = substr(md5(config('key.aeskey')),2,20);
        $this->iv = substr(md5(config('key.aesvi')),3,16);
    }

    /**
     * 加密
     * @param String input 加密的字符串
     * @return HexString
     */
    public function encrypt($input = '') {
        $data=openssl_encrypt($input, 'AES-128-CBC',$this->key,OPENSSL_RAW_DATA,$this->iv);
        $encrypt=base64_encode($data);
        return $encrypt;
    }
    /**
     * 解密
     * @param String input 解密的字符串
     * @return String
     */
    public function decrypt($sStr) {
        $encrypt = base64_decode($sStr);
        $decrypt = openssl_decrypt($encrypt, 'AES-128-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);
        return $decrypt;
    }

}