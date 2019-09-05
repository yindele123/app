<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if (!function_exists('show')) {
    function show($status,$message,$data=[],$code=200){
        $result=[
            'status'=>$status,
            'message'=>$message,
            'data'=>$data
        ];
        return json($result,$code);
    }
}
/***
 * 随机数生成生成
 */
if (!function_exists('getNumberCode')) {
    function getNumberCode($length = 6)
    {
        $code = '';
        for ($i = 0; $i < intval($length); $i++) {
            $code .= rand(0, 9);
        }
        return $code;
    }
}

/**
 * 获取栏目名称
 * @param $catId
 */
function getCatName($catId) {
    if(!$catId) {
        return '';
    }
    $catename='';
    $cats = \app\common\model\Cate::getListCate();
    foreach($cats as $key=>$val){
        if($val['id']==$catId){
            $catename=$val['catename'];
        }
    }

    return !empty($catename) ? $catename : '';
}

function isYesNo($str) {
    return $str ? '<span style="color:red"> 是</span>' : '<span > 否</span>';
}

/**
 * description: 查找指定pid的数量总和（判断最后一个）
 * @param array $array
 * @param string $keyname
 * @param int $value
 * @return boolean
 */
function searchPidCount($array, $keyname, $value)
{
    $countNum = 0;
    foreach ($array as $key => $val) {
        if ($val[$keyname] == $value) {
            $countNum++;
        }
    }
    return $countNum;
}

/**
 * description: 二维数组查找指定的值知否存在
 * @param array $array
 * @param string $keyname
 * @param int $value
 * @return boolean
 */

function searchArrayVal($array, $keyname, $value)
{
    foreach ($array as $key => $val) {
        if ($val[$keyname] == $value) {
            return true;
        }
    }
    return false;
}