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
class Common{
    public static function getPageAndSize($data) {
        $result=[];
        $result['page'] = !empty($data['page']) ? $data['page'] : 1;
        $result['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $result['from'] = ($result['page'] - 1) * $result['size'];
        return $result;
    }
}
