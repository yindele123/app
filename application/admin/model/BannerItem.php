<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/25 0025
 * Time: 12:03
 */
namespace app\admin\model;
class BannerItem extends BaseModel{

    public function item(){
        return $this->belongsTo('Banner','banner_id')->field('id,name,identification');
    }
    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'title',
                'url',
                'banner_id',
                'start_time',
                'end_time',
                'image',
                'status',
                'create_time'
            ];
        }
        return $field;
    }
    public function getAdS($where=[],$param=[]){
        $param=$this->setWhereField($param);
        $result = $this->where($where)->with(['item'])->field(self::getListField($param['field']))->order($param['order'])
            ->paginate($param['size'],false,['page' => $param['page']]);
        return $result;
    }
}