<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 15:29
 */
namespace app\admin\model;
class Cate extends BaseModel{
    /**
     * 获取递归分类
     */
    public function catetree($field=''){
        $cateres=$this->order('sort desc')->field($this->getListField($field))->select();
        return collection($this->sort($cateres))->toArray();
    }

    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'name',
                'pid',
                'sort',
                'type',
                'status'
            ];
        }
        return $field;
    }

    /**
     * 获取所有分类
     * @param
     * @return array
     */
    public static function getListCate(){
        $cateres=self::field('id,name')->select();
        return collection($cateres)->toArray();
    }


}