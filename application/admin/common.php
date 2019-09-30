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