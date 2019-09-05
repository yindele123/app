<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 12:56
 */

/**
 * 获取分页page size 内容
 */

namespace app\common\service;
use think\Log;

class Common
{
    public static function getPageAndSize($data)
    {
        $result = [];
        $result['page'] = !empty($data['page']) ? $data['page'] : 1;
        $result['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $result['from'] = ($result['page'] - 1) * $result['size'];
        return $result;
    }

    //配置组装where条件
    public static function setWhere($where)
    {
        $new_arr = null;
        foreach ($where as $k => $v) {
            if (!empty($v)) {
                foreach ($v as $val => $key) {
                    $new_arr[$val] = $key;
                }
            }
        }
        return $new_arr;
    }

    //配置获取条数
    public static function setCount($count, $initial = 10, $highest = 20)
    {
        if (empty($count) || $count > $highest) {
            $count = $initial;
        } else {
            $count = $count;
        }
        return $count;
    }

    //写入错误日志
    public static function setLog($e)
    {
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e, 'error');
    }

    public static function getMenu($pid){
        $menu=model('Menu')->field('id,name,value')->where(['pid'=>2,'status'=>1])->select();
        return collection($menu)->toArray();
    }
}
