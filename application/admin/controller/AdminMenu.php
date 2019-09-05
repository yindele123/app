<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/5 0005
 * Time: 18:52
 */
namespace app\admin\controller;
class AdminMenu extends BaseController{
    public function index(){
        try{
            $cateres=model('Cate')->catetree();
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        return $this->fetch('index',[
            'cateres'=>$cateres
        ]);
    }
}