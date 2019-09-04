<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 11:49
 */
namespace app\common\model;
class News extends CommonModel {
    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'catid',
                'image',
                'title',
                'read_count',
                'status',
                'is_position',
                'update_time',
                'create_time'
            ];
        }
        return $field;
    }

    /**
     * 根据来获取列表的数据
     * @param int $where  查询条件
     * @param int $catid  查询的分类，多级分类自行组装传进来
     * @param int $from    起始获取值
     * @param int $size    获取多少条数据
     * @param int $title    模糊搜索的新闻标题
     * @param int $field  要查询的字段
     * @param int $order  排序
     * @param array $result  返回按条件获取的新闻列表
     */
    public function getNewsByCondition($where = [], $catid=[], $from=0, $size = 5,$title='',$field='',$order = ['id' => 'desc']) {
        $model=new News;
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
        $result = $this->where($where)->limit($from, $size)->field(self::getListField($field))->order($order)->select();
        return $result;
    }

    /**
     * 根据条件来获取列表的数据的总数
     * @param array $where  查询条件
     * @param array $catid  查询的分类，多级分类自行组装传进来
     * @param string $title  模糊查询的新闻标题
     * @param return $data
     */
    public function getNewsCountByCondition($where = [], $catid=[],$title='') {
        $model=new News;
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
     * 查询新闻详细
     * @param int $id  查询新闻ID
     * @param int $field  要查询的字段
     * @return $data
     */
    public function getNewsFind($id,$field=[]) {
        if(empty($id)){
            return false;
        }
        $data=$this->where(['id'=>$id])->field($field)->find();
        return $data;
    }
}