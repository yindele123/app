<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 11:56
 */
namespace app\api\model;

use app\common\model\CommonModel;

class BaseModel extends CommonModel {
    protected $hidden = ['delete_time'];

    protected function  prefixImgUrl($value, $data){
        $finalUrl = $value;
        if($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}