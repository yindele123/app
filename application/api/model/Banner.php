<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:51
 */
namespace app\api\model;
class Banner extends BaseModel
{
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }
    //

    /**
     * @param $id int banner所在位置
     * @return Banner
     */
    public static function getBannerById($id)
    {
        $banner = self::with(['items','items.img'])
            ->find($id);
        return $banner;
    }
}
