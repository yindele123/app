<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 20:41
 */
namespace app\admin\model;
class AuthRule extends BaseModel{
    /**
     * 获取递归分类
     */
    public function ruleTree($field='',$assembly=false){
        $cateres=$this->order('sort desc,id desc')->field($this->getListField($field))->select();
        return $assembly==true ? collection($this->sort($cateres,0,0,true))->toArray() : collection($this->sort($cateres))->toArray();
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
                'title',
                'status'
            ];
        }
        return $field;
    }
}