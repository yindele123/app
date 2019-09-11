<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/11 0011
 * Time: 16:37
 */
namespace app\common\model;
use think\Cache;
class Version extends CommonModel{
    /**
     * 通过apptype获取最后一条版本内容
     * @param string $appType
     */
    public function getLastNormalVersionByAppType($appType = '',$cache='version',$cacheTime=86400) {
        $result = Cache::get($cache.$appType);

        if (empty($result)) {
            $data = [
                'status' => 1,
                'app_type' => $appType,
            ];

            $order = [
                'id' => 'desc',
            ];

            $result=$this->where($data)->order($order)->limit(1)->find()->toArray();
            if($result){
                Cache::set($cache.$appType, $result, $cacheTime);
            }else{
                $result=Cache::remember($cache.$appType,function() use ($result){
                    return time();
                },$cacheTime);
            }
        }
        return $result;
    }
}