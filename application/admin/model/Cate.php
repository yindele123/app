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
    //,$type='',$field=''
    public function catetree($param=[]){
        $param=$this->setWhereField($param);
        $cate=new Cate;
        if(!empty($param['type'])){
            $cate->where( 'FIND_IN_SET(' . $param['type'] . ',type)');
        }
        $cateres=$cate->order($param['order'])->field($this->getListField($param['field']))->select();
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

    protected function setWhereField($param=[]){
        if(!isset($param['type'])) $param['type']='';
        if(!isset($param['field'])) $param['field']='';
        if(!isset($param['order'])) $param['order']=['sort'=>'desc'];
        return $param;
    }


}