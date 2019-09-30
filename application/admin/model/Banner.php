<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/25 0025
 * Time: 11:20
 */
namespace app\admin\model;
class Banner extends BaseModel{
    //关联广告
    public function item(){
        return $this->hasMany('BannerItem','banner_id');
    }
    /***
     * @param string $key 搜索
     * @param int $page  页码
     * @param int $size  取值多少条
     * @return array $data
     */
    public static function getBanners($key='',$page=1,$size=10){
        $model=new Banner;
        if(!empty($key)){
            $model->where('name|identification', 'like', '%' . trim($key) . '%');
        }
        $data=$model->order('id desc')->withCount('item')->field('id,name,identification,create_time,status')->paginate($size,false,['page' => $page]);
        return $data;
    }

    public function getBannerById($id){
        $banner = self::withCount(['item'])
            ->find($id);
        return $banner;
    }
}