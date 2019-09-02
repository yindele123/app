<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 12:47
 */
/**
 * 栏目接口
 */
namespace app\api\controller\v1;
use app\api\controller\BaseController;

class Cat extends BaseController{
    public function read() {
        $cats = config('cat.lists');

        $result[] = [
            'catid' => 0,
            'catname' => '首页',
        ];

        foreach($cats as $catid => $catname) {
            $result[] = [
                'catid' => $catid,
                'catname' => $catname,
            ];
        }

        return show(config('code.success'), 'OK', $result, 200);
    }
}

