<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 11:49
 */
namespace app\api\model;
use app\common\model\News as CommNews;
class News extends BaseModel {
    /**
     * 获取首页头图数据
     * @param int $num
     * @return array
     */
    public function getIndexHeadNormalNews($num = 4,$field=[]) {
        $data = [
            'status' => 1,
            'is_head_figure' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field(CommNews::getListField($field))
            ->order($order)
            ->limit($num)
            ->select();
    }

    /**
     * 获取推荐的数据
     */
    public function getPositionNormalNews($num = 20) {
        $data = [
            'status' => 1,
            'is_position' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field(CommNews::getListField())
            ->order($order)
            ->limit($num)
            ->select();

    }

    /**
     * 获取新闻排行榜数据
     * @param int $where  查询条件
     * @param int $field  要查询的字段
     * @param int $catid  栏目ID
     * @param int $num    获取条数
     * @param int $order  排序
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRankNormalNews($where=[],$field=[],$catid=[],$num = 5,$order=['read_count' => 'desc']) {
        $model=new News;
        if(!empty($catid)){
            $model->where('catid','in',$catid);
        }
        if(!isset($where['status'])) {
            $where['status'] = config('code.status_normal');
        }
        $data=$model->where($where)->field(CommNews::getListField($field))->order($order)->limit($num)->select();
        return $data;
    }
}