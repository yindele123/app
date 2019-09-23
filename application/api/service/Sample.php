<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:00
 */
namespace app\api\service;

use app\api\model\Sample as SampleModel;

/**
 *Sample服务类
 */
class Sample
{
    /**
     * @param $key int banner所在位置
     * @return false|static[]
     */
    static public function getSampleByKey($key)
    {
//        $banner = new BannerModel();
//        $banners = $banner->where('key', $key)->find();
        $banners = SampleModel::all(['key' => $key]);
        return $banners;
    }
}