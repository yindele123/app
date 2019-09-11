<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/11 0011
 * Time: 10:23
 */
namespace app\common\model;
use think\Cache;

class Cate extends CommonModel{
    /***
     * 获取所有新闻分类
     * @param string $cache 缓存名称
     * @param int $cacheTime  缓存时间
     * @param string $field  需要获取的字段
     * @return array
     */
    public function getCate($cache='cate',$cacheTime=300,$field=''){
        $cateres = Cache::get($cache);
        if (empty($cateres)) {
            $cateres = $this->order('sort desc,id desc')->field(self::getListField($field))->select();
            Cache::set($cache, $cateres, $cacheTime);
        }
        return collection($cateres)->toArray();
    }
    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'name',
                'pid'
            ];
        }
        return $field;
    }

    /**
     * 获取所有子分类
     * @param $cateid 父ID
     * @return array
     */
    public function getCatechilrenid($cateid,$cache='cateChilrenId',$cacheTime=86400){
        $cateres = Cache::get($cache.$cateid);
        if (empty($cateres)) {
            $cateres = $this->field(['id', 'name','pid'])->select();
            Cache::set($cache.$cateid, $cateres, $cacheTime);
        }
        return getColumn($cateres,$cateid);
    }
}