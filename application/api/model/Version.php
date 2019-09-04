<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 12:29
 */
namespace app\api\model;
class Version extends BaseModel{
    /**
     * 通过apptype获取最后一条版本内容
     * @param string $appType
     */
    public function getLastNormalVersionByAppType($appType = '') {
        $data = [
            'status' => 1,
            'app_type' => $appType,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)->order($order)->find();
    }
}