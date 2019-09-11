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
            $result = $model->where($where)->with(['cateFind'])->limit($from, $size)->field(self::getListField($field))->order($order)->select();
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

    /**
     * 公共根据条件来获取列表的数据的总数
     * @param array $where  查询条件
     * @param array $catid  查询的分类
     * @param string $title  模糊查询的新闻标题
     * @param return $data
     */
    public function getNewsCount($cache='newslistCount',$cacheTime=500,$where = [], $catid=0,$title='') {
        $data = Cache::get($cache.$catid.$title);
        if (empty($data)) {
            $model = $this;
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
            $data = $model->where($where)->count();
            if($data){
                Cache::set($cache.$catid.$title, $data, $cacheTime);
            }else{
                $data=Cache::remember($cache.$catid.$title,function() use ($data){
                    return time();
                },$cacheTime);
            }
        }
        return $data;
    }

    /***
     * 获取新闻通用字段
     * @param string $field
     * @return array|string
     */
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
    /**
     * 查询新闻详细
     * @param int $id  查询新闻ID
     * @param int $field  要查询的字段
     * @return $data
     */
    public function getNewsFind($cache='newsFind',$cacheTime=1000,$id,$field=[]) {
        $data = Cache::get($cache.$id);
        if (empty($data)) {
            if (empty($id)) {
                return false;
            }
            $data = $this->where(['id' => $id])->with('cateFind')->field(self::getListField($field))->find();
            if($data){
                Cache::set($cache.$id, $data, $cacheTime);
            }else{
                $data=Cache::remember($cache.$id,function() use ($data){
                    return time();
                },$cacheTime);
            }
        }
        return $data;
    }
    /**
     * 关联分类表
     * @return \think\model\relation\HasOne
     */
    public function cateFind(){
        return $this->belongsTo('Cate','catid')->field('id,name');
    }
}