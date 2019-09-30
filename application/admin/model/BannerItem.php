<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/25 0025
 * Time: 12:03
 */
namespace app\admin\model;
class BannerItem extends BaseModel{

    public function getAdS($bannerId){
        $result = $this->where(['banner_id'=>$bannerId])->field(self::getListField($param['field']))->order($param['order'])
            ->paginate($param['size'],false,['page' => $param['page']]);
        return $result;
    }
}