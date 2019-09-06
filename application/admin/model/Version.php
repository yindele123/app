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
    public function getVersionByCondition($where = [], $from=0, $size = 5,$order = ['id' => 'desc'])
    {
        $model=new Version;
        if(!isset($where['status'])) {
            $where['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $result = $model->where($where)->limit($from, $size)->field('id,app_type,version,version_code,is_force,apk_url,upgrade_point,status,create_time')->order($order)->select();
        return collection($result)->toArray();
    }
}