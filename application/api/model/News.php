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
            ->field(CommNews::getListField())
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
     * 获取排行榜数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRankNormalNews($num = 5) {
        $data = [
            'status' => 1,
        ];

        $order = [
            'read_count' => 'desc',
        ];

        return $this->where($data)
            ->field(CommNews::getListField())
            ->order($order)
            ->limit($num)
            ->select();
    }
}