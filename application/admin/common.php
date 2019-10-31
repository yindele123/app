<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/30 0030
 * Time: 19:37
 */
if (!function_exists('timeStatus')) {
    function timeStatus($start_time,$end_time)
    {
        //$time=strtotime(date('Y-m-d'));
        $time=time();
        $str='';
        if($time >= $start_time && $time <= $end_time){
            $str="正常(还有".timeBefore($end_time-$time)."到期)";
        }elseif ($end_time < $time){
            $str="广告截止了(".timeBefore($time-$end_time)."前)";
        }elseif ($start_time > $time){
            $str="广告还有(".timeBefore($start_time-$time)."开始)";
        }
        return $str;
    }
}

if (!function_exists('timeBefore')) {
    function timeBefore($time)
    {
        if ($time < 60) {
            return $time . '秒';
        } else {
            if ($time < 3600) {
                return floor($time / 60) . '分钟';
            } else {
                if ($time < 86400) {
                    return floor($time / 3600) . '小时';
                } else {
                    return floor($time / 86400) . '天';
                }
            }
        }
    }
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
if (!function_exists('check_document_position')) {
    function check_document_position($pos = 0, $contain = 0)
    {
        if (empty($pos) || empty($contain)) {
            return false;
        }

        //将两个参数进行按位与运算，不为0则表示$contain属于$pos
        $res = $pos & $contain;
        if ($res !== 0) {
            return true;
        } else {
            return false;
        }
    }
}