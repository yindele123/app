<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 13:20
 */
namespace app\common\model;
use app\admin\model\BaseModel;

class Version extends BaseModel{
    public function getVersionByCondition($where = [], $param=[])
    {
        $param=$this->setWhereField($param);
        $model=new Version;
        if(!isset($where['status'])) {
            $where['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $result = $model->where($where)->limit($param['from'], $param['size'])->field('id,app_type,version,version_code,is_force,apk_url,upgrade_point,status,create_time')->order($param['order'])->select();
        return collection($result)->toArray();
    }
}