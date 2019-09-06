<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 15:26
 */
namespace app\admin\model;

use app\common\model\CommonModel;

class BaseModel extends CommonModel
{
    // 软删除，设置后在查询时要特别注意whereOr
    // 使用whereOr会将设置了软删除的记录也查询出来
    // 可以对比下SQL语句，看看whereOr的SQL
    /*use SoftDelete;

    protected $hidden = ['delete_time'];
    protected $deleteTime = 'delete_time';

    protected function  prefixImgUrl($value, $data){
        $finalUrl = $value;
        if($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }*/

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


    /**
     * 公共根据条件来获取列表的数据的总数
     * @param array $where  查询条件
     * @param array $catid  查询的分类，多级分类自行组装传进来
     * @param string $title  模糊查询的新闻标题
     * @param return $data
     */
    public function getCountByCondition($where = [], $catid=[],$title='') {
        $model=$this;
        if(!empty($catid)){
            $model->where('catid','in',$catid);
        }
        if(!empty($title)) {
            $where['title'] = ['like', '%'.trim($title).'%'];
        }
        if(!isset($where['status'])) {
            $where['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $data=$model->where($where)->count();
        return $data;
    }

    /**
     * 获取公共分类层级树形结构数组
     * @param $cateid 父ID
     * @return array
     */
    public function getCateList($field=[]){
        $cateres=$this->field($field)->select();
        return getCategoryTree($cateres);
    }

    /**
     * 获取所有子分类
     * @param $cateid 父ID
     * @return array
     */
    public function getchilrenid($cateid){
        $cateres=$this->field(['id', 'name', 'pid', 'sort', 'status'])->select();
        return getColumn($cateres,$cateid);
    }

}