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
        $cateres=$this->order('sort desc')->order(['sort'=>'desc'])->field(CommonCate::getListField($field))->select();
        return collection($this->sort($cateres))->toArray();
    }
    /**
     * 递归分类
     * @param $data 所有分类
     * @param int $pid 分类父ID
     * @param int $level  分类等级
     * @return array
     */
    public function sort($data,$pid=0,$level=0){
        static $arr=[];
        foreach ($data as $k => $v) {
            $data[$k]['subclass']=self::hasChild($data,$v['id']);
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                unset($data[$k]);
                $this->sort($data,$v['id'],$level+1);
            }
        }

        return $arr;
    }

    /** 判断是否有子分类
     * @param $cate
     * @param $id
     * @return bool
     */
    static public function hasChild($cate, $id) {
        $arr = false;
        foreach ($cate as $v) {
            if ($v['pid'] == $id) {
                $arr = true;
                return $arr;
            }
        }
        return $arr;
    }

}