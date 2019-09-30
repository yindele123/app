<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/25 0025
 * Time: 12:03
 */
namespace app\admin\model;
class BannerItem extends BaseModel{

    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'title',
                'url',
                'start_time',
                'end_time',
                'image',
                'status',
                'create_time'
            ];
        }
        return $field;
    }
    public function getAdS($bannerId,$param=[]){
        $param=$this->setWhereField($param);
        $result = $this->where(['banner_id'=>$bannerId])->field(self::getListField($param['field']))->order($param['order'])
            ->paginate($param['size'],false,['page' => $param['page']]);
        return $result;
    }
}