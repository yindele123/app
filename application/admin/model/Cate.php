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
        $cateres=$this->order('sort desc')->field(CommonCate::getListField($field))->select();
        return collection($this->sort($cateres))->toArray();
    }
}