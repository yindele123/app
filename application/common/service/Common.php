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
use app\lib\exception\ErrorException;
use think\Cache;
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
        $menu=model('Menu')->field('id,name,value')->order(['sort'=>'desc','id'=>'desc'])->where(['pid'=>$pid,'status'=>1])->select();
        return collection($menu)->toArray();
    }

    /**
     * 通用获取ID判断
     * @return mixed|void
     */
    public function usuallyId($id,$model='',$msg='请不要非法操作',$cache='usuallyId',$cacheTime=500)
    {
        $cate = Cache::get($cache.$id);
        if (empty($cate)) {
            try {
                $cate = model($model)::get($id);
            } catch (\Exception $e) {
                Common::setLog(request()->url() . '-----' . $e->getMessage());
                throw new ErrorException();
            }
            if (empty($cate)) {
                throw new ErrorException(['msg' => $msg]);
            }
            Cache::set($cache.$id, $cate, $cacheTime);
        }
        return $cate;
    }

    /****
     * 处理空查询缓存
     * @param $str 要判断的字符串
     */
    public static function isNumeric($str){
        if(is_numeric($str)){
            $str=[];
        }
        return $str;
    }

}
