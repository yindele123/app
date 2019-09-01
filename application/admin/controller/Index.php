<?php
namespace app\admin\controller;


class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }
    public function welcome(){
        return $this->fetch();
    }

}
