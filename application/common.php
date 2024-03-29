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
    function show($status, $message, $data = [], $code = 200)
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        return json($result, $code);
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
if (!function_exists('getCatName')) {
    function getCatName($cats, $catId)
    {
        if (!$catId || !$cats) {
            return '';
        }
        $name = '';
        foreach ($cats as $key => $val) {
            if ($val['id'] == $catId) {
                $name = $val['name'];
            }
        }
        return !empty($name) ? $name : '';
    }
}
if (!function_exists('getMenuName')) {
    function getMenuName($menuData, $value)
    {
        if (empty($menuData)) {
            return false;
        }
        $value=explode(',',$value);
        $data = '';
        foreach ($menuData as $key => $val) {
            if (in_array($val['value'],$value)) {
                $data .= $val['name'].',';
            }
        }
        return trim($data,',');
    }
}


if (!function_exists('isYesNo')) {
    function isYesNo($str)
    {
        return $str ? '<span style="color:red"> 是</span>' : '<span > 否</span>';
    }
}

/**
 * description: 查找指定pid的数量总和（判断最后一个）
 * @param array $array
 * @param string $keyname
 * @param int $value
 * @return boolean
 */
if (!function_exists('searchPidCount')) {
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
}

/**
 * description: 二维数组查找指定的值知否存在
 * @param array $array
 * @param string $keyname
 * @param int $value
 * @return boolean
 */
if (!function_exists('searchArrayVal')) {
    function searchArrayVal($array, $keyname, $value)
    {
        foreach ($array as $key => $val) {
            if ($val[$keyname] == $value) {
                return true;
            }
        }
        return false;
    }
}
/**
 * 组装获取子分类
 * @param $cateres  所有分类内容
 * @param $cateid   父ID
 * @return array
 */
if (!function_exists('getColumn')) {
    function getColumn($cateres, $cateid)
    {
        static $arr = [];
        foreach ($cateres as $k => $v) {
            if ($v['pid'] == $cateid) {
                $arr[] = $v['id'];
                getColumn($cateres, $v['id']);
            }
        }
        return $arr;
    }
}

/**
 * description: 递归菜单
 * @param unknown $array
 * @param number $fid
 * @param number $level
 * @param number $type 1:顺序菜单 2树状菜单
 * @return multitype:number
 */
if (!function_exists('getCategoryTree')) {
    function getCategoryTree($array, $pid = 0, $level = 0)
    {
        $column = [];
        $i = 0;
        $thisCount = 0;
        $nbsp = "　";
        $icon = "─";
        foreach ($array as $key => $vo) {
            if ($vo['pid'] == $pid) {
                $vo['level'] = $level;
                $i++;
                if ($level > 0) {
                    $vo['html'] = str_repeat($nbsp, $level) . '├──' . str_repeat($icon, $level);
                    $thisCount = searchPidCount($array, 'pid', $vo['pid']);
                    if ($i == $thisCount) {
                        $vo['html'] = str_repeat($nbsp, $level) . '└──' . str_repeat($icon, $level);
                    }
                } else {
                    $vo['html'] = '';
                }
                $vo['child'] = searchArrayVal($array, 'pid', $vo['id']) ? 1 : 0;
                $column[] = $vo;
                $column = array_merge($column, getCategoryTree($array, $vo['id'], $level + 1));
            }
        }
        return $column;
    }
}

/**
 * @param $AuthRuleRes 所以权限分类
 * @param $authRuleId 分类ID
 * @param bool $clear 静态数组重新赋值
 * @return string
 */
if (!function_exists('getparentid')) {
    function getparentid($AuthRuleRes, $authRuleId, $clear = False)
    {
        static $arr = array();
        if ($clear) {
            $arr = array();
        }
        foreach ($AuthRuleRes as $k => $v) {
            if ($v['id'] == $authRuleId) {
                $arr[] = $v['id'];
                getparentid($AuthRuleRes, $v['pid'], False);
            }
        }
        asort($arr);
        $arrStr = implode('-', $arr);
        return $arrStr;
    }
}

/***
 * @param
 * @return array 返回APP类型一维数组
 */
if (!function_exists('apptype')) {
    function apptype()
    {
        $apptypes = config('app.apptypes');
        $apptypeA = [];
        foreach ($apptypes as $v) {
            foreach ($v as $key => $vv) {
                $apptypeA[] = $vv;
            }
        }
        return $apptypeA;
    }
}
/***
 * @param $apptypeS header传输过来的apptype
 * @return string 返回APP类型ID
 */
if (!function_exists('apptypeValue')) {
    function apptypeValue($apptypeS)
    {
        $apptypes = config('app.apptypes');
        $apptype = '';
        foreach ($apptypes as $v) {
            foreach ($v as $key => $vv) {
                if ($apptypeS == $vv) {
                    $apptype = $key;
                    break;
                }
            }
        }
        return $apptype;
    }
}
// 转换查询条件
if (!function_exists('setCheckTime')) {
    function setCheckTime($data)
    {
        $whereData = [];
        if (!empty($data['start_time']) && !empty($data['end_time'])
            && $data['end_time'] > $data['start_time']
        ) {
            $whereData['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        return $whereData;
    }
}

/**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
if (!function_exists('curl_post')) {
    function curl_post($url, array $params = array())
    {
        $data_string = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }
}

if (!function_exists('curl_post_raw')) {
    function curl_post_raw($url, $rawData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text'
            )
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }
}

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
if (!function_exists('curl_get')) {
    function curl_get($url, &$httpCode = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //不做证书校验,部署在linux环境下请改为true
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $file_contents = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $file_contents;
    }
}

if (!function_exists('getRandChar')) {
    function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }
}
if (!function_exists('fromArrayToModel')) {
    function fromArrayToModel($m, $array)
    {
        foreach ($array as $key => $value) {
            $m[$key] = $value;
        }
        return $m;
    }
}
