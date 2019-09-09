<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/9 0009
 * Time: 17:44
 */
namespace app\admin\model;
class AuthGroup extends BaseModel{
    public function authRroupList($where = [],$field=''){
        $data=$this->order('id desc')->where($where)->field(self::getListField($field))->select();
        return collection($data)->toArray();
    }

    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'title',
                'status',
                'description'
            ];
        }
        return $field;
    }
}