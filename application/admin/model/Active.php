<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 16:23
 */
namespace app\admin\model;
class Active extends BaseModel{
    /***
     * 按照版本号app类型统计使用情况
     * @param array $where 条件
     * @param int $from 取数据开始取
     * @param int $size 取多少条
     * @return array
     */
    public function getActiveList($where = [], $param=[])
    {
        $param=$this->setWhereField($param);
        $result = $this->where($where)->limit($param['from'], $param['size'])->field('id,version,app_type,version_code,did,create_time,count(id) as number')->group('version,app_type')->order('number desc')->select();
        return collection($result)->toArray();
    }

    /***
     * 按照版本号app类型统计总数
     * @param array $where  条件
     * @return int|string
     * @throws \think\Exception
     */
    public function getActiveCount($where = []) {
        $data=$this->where($where)->group('version,app_type')->count();
        return $data;
    }

    /***
     * 查看那个版本号那个APP类型的使用情况详情
     * @param array $where 条件
     * @param int $from 取数据开始取
     * @param int $size 取多少条
     * @return array
     */
    public function getActiveDetailedList($where = [], $param=[])
    {
        $param=$this->setWhereField($param);
        $result = $this->where($where)->limit($param['from'], $param['size'])->field('id,version,app_type,version_code,did,create_time,did')->order($param['order'])->select();
        return collection($result)->toArray();
    }


}