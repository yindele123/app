<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/4 0004
 * Time: 19:33
 */
namespace app\common\model;
class Cate extends CommonModel {
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
        return $this->_getchilrenid($cateres,$cateid);
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
     * 组装获取子分类
     * @param $cateres  所有分类内容
     * @param $cateid   父ID
     * @return array
     */
    private function _getchilrenid($cateres,$cateid){
        static $arr=[];
        foreach ($cateres as $k => $v) {
            if($v['pid'] == $cateid){
                $arr[]=$v['id'];
                $this->_getchilrenid($cateres,$v['id']);
            }
        }
        return $arr;
    }

    /**
     * 获取公共分类层级树形结构数组
     * @param $cateid 父ID
     * @return array
     */
    public function getCateList($field=''){
        $cateres=$this->field(self::getListField($field))->select();
        return $this->getCategoryTree($cateres);
    }

    /**
     * description: 递归菜单
     * @param unknown $array
     * @param number $fid
     * @param number $level
     * @param number $type 1:顺序菜单 2树状菜单
     * @return multitype:number
     */
    public function getCategoryTree($array, $pid = 0, $level = 0)
    {
        $column = [];
        $i = 0;
        $thisCount = 0;
        $nbsp = "　";
        $icon = "─";
        foreach ($array as $key => $vo) {
            if ($vo['pid'] == $pid) {
                $vo['level'] = $level;
                $i++;
                if ($level > 0) {
                    $vo['html'] = str_repeat($nbsp, $level) . '├──' . str_repeat($icon, $level);
                    $thisCount = searchPidCount($array, 'pid', $vo['pid']);
                    if ($i == $thisCount) {
                        $vo['html'] = str_repeat($nbsp, $level) . '└──' . str_repeat($icon, $level);
                    }
                } else {
                    $vo['html'] = '';
                }
                $vo['child'] = searchArrayVal($array, 'pid', $vo['id']) ? 1 : 0;
                $column[] = $vo;
                $column = array_merge($column, $this->getCategoryTree($array, $vo['id'], $level + 1));
            }
        }
        return $column;
    }

}