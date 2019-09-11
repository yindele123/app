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
use app\common\service\Common;
use app\lib\exception\ErrorException;
use app\lib\PHPTree;

class Cat extends BaseController{
    public function read() {
        try{
            $cate=model('Cate')->getCate();
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
        $cateTree=(new PHPTree())::makeTree($cate);
        return show(config('code.success'), 'OK', $cateTree, 200);
    }
}

