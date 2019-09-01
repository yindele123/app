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
    $cats = config('cat.lists');

    return !empty($cats[$catId]) ? $cats[$catId] : '';
}

function isYesNo($str) {
    return $str ? '<span style="color:red"> 是</span>' : '<span > 否</span>';
}

/**
 * 状态
 * @param $id
 * @param $status
 */
function status($id, $status) {
    $controller = request()->controller();

    $sta = $status == 1 ? 0 : 1;
    $url = url($controller.'/status', ['id' => $id, 'status' => $sta]);

    if($status == 1) {
        $str = "<a href='javascript:;' title='修改状态' status_url='".$url."' onclick='app_status(this)'><span class='layui-btn layui-btn-normal layui-btn-mini'>正常</span></a>";
    }elseif($status == 0) {
        $str = "<a href='javascript:;' title='修改状态' status_url='".$url."' onclick='app_status(this)'><span class='layui-btn layui-btn-danger layui-btn-mini'>待审</span></a>";
    }

    return $str;
}