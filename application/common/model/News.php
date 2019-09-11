<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/11 0011
 * Time: 10:38
 */
namespace app\common\model;
use think\Cache;
class News extends CommonModel{
    /**
     * 根据来获取列表的数据
     * @param string $cache  缓存名称
     * @param int $cacheTime  缓存时间
     * @param int $where  查询条件
     * @param int $catid  查询的分类
     * @param int $from    起始获取值
     * @param int $size    获取多少条数据
     * @param int $title    模糊搜索的新闻标题
     * @param int $field  要查询的字段
     * @param int $order  排序
     * @param array $result  返回按条件获取的新闻列表
     */
    public function getNewsList($cache='newslist',$cacheTime=500,$where = [], $catid=0, $from=0, $size = 5,$title='',$field='',$order = ['id' => 'desc'])
    {
        $result = Cache::get($cache.$catid.$from.$size.$title);
        if (empty($result)) {
            $model = new News;
            if (!empty($catid)) {
                $cate=new Cate;
                $catidArray=$cate->getCatechilrenid($catid);
                $catidArray[]=$catid;
                $model->where('catid', 'in', $catidArray);
            }
            if (!empty($title)) {
                $where['title'] = ['like', '%' . trim($title) . '%'];
            }
            if (!isset($where['status'])) {
                $where['status'] = [
                    'neq', config('code.status_delete')
                ];
            }
            $result = $model->where($where)->limit($from, $size)->field(self::getListField($field))->order($order)->select();
            if($result){
                Cache::set($cache.$catid.$from.$size.$title, $result, $cacheTime);
            }else{
                $result=Cache::remember($cache.$catid.$from.$size.$title,function() use ($result){
                    return time();
                },$cacheTime);
            }
        }
        return $result;
    }

    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'catid',
                'image',
                'title',
                'create_time'
            ];
        }
        return $field;
    }
}