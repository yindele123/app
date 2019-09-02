<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 11:49
 */
namespace app\common\model;
class News extends BaseModel {
    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($fiel='') {
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
     * @param array $param
     */
    public function getNewsByCondition($condition = [], $from=0, $size = 5) {
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $order = ['id' => 'desc'];

        $result = $this->where($condition)
            ->limit($from, $size)
            ->field(self::getListField())
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * 根据条件来获取列表的数据的总数
     * @param array $param
     */
    public function getNewsCountByCondition($condition = []) {
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        return $this->where($condition)
            ->count();
        //echo $this->getLastSql();
    }

    /**
     * 后台自动化分页
     * @param array $data
     */
    public function getNews($data = []) {
        $data['status'] = [
            'neq', config('code.status_delete')
        ];

        $order = ['id' => 'desc'];
        // 查询

        $result = $this->where($data)
            ->order($order)
            ->paginate();
        // 调试
        return $result;
    }
}