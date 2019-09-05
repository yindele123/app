<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 15:29
 */
namespace app\admin\model;
use app\common\model\Cate as CommonCate;
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
                'catename',
                'pid',
                'sort',
                'status'
            ];
        }
        return $field;
    }
    /**
     * 获取所有子分类
     * @param $cateid 父ID
     * @return array
     */
    public function getchilrenid($cateid,$field=''){
        $cateres=$this->field(self::getListField($field))->select();
        return getColumn($cateres,$cateid);
    }

    /**
     * 获取所有分类
     * @param
     * @return array
     */
    public static function getListCate(){
        $cateres=self::field('id,catename')->select();
        return collection($cateres)->toArray();
    }



    /**
     * 获取公共分类层级树形结构数组
     * @param $cateid 父ID
     * @return array
     */
    public function getCateList($field=''){
        $cateres=$this->field(self::getListField($field))->select();
        return getCategoryTree($cateres);
    }
}