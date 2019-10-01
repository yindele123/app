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
    public function getBanners($param=[]){
        $param=$this->setWhereField($param);
        $model=new Banner;
        if(!empty($param['title'])){
            $model->where('name|identification', 'like', '%' . trim($param['title']) . '%');
        }
        $data=$model->order($param['order'])->withCount('item')->field('id,name,identification,create_time,status')->paginate($param['size'],false,['page' => $param['page']]);
        return $data;
    }

    public function getBannerById($id){
        $banner = self::withCount(['item'])
            ->find($id);
        return $banner;
    }
}