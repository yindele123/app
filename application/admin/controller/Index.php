<?php
namespace app\admin\controller;


class Index extends BaseController
{
    public function index()
    {
        return $this->fetch('index',[
            'user'=>$this->user
        ]);
    }
    public function welcome(){
        return $this->fetch();
    }

}
