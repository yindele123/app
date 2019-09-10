<?php
namespace app\admin\controller;


use app\common\service\Common;
use think\Request;
use app\lib\PHPTree;

class Index extends BaseController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('user',$this->user);
    }

    public function index()
    {
        $this->model='AuthGroup';
        $authGroup=$this->usuallyId($this->user['auth_group_access_find']['group_id']);
        try{
            $authRule=model('AuthRule')->where('status','1')->field('id,pid,name,title')->order('sort asc')->select();
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('index/index'),6,3);
        }
        return $this->fetch('index',[
            'authRule'=>(new PHPTree())::makeTree($authRule),
            'rules'=>empty($authGroup['rules']) ? [] : explode(',',$authGroup['rules'])
        ]);
    }

    public function welcome(){
        try{
            $active=model('Active')->getActiveList();
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('index/index'),6,3);
        }
        try{
            $newsTotal = model('News')->getCountByCondition();
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('index/index'),6,3);
        }
        return $this->fetch('',[
            'active'=>$active,
            'apptype'=>Common::getMenu(1),
            'newsTotal'=>$newsTotal,
            'newsToday'=>model('News')->whereTime('create_time', 'today')->count(),
            'newsYesterday'=>model('News')->whereTime('create_time', 'yesterday')->count(),
            'newsWeek'=>model('News')->whereTime('create_time', 'week')->count(),
            'newsLastWeek'=>model('News')->whereTime('create_time', 'last week')->count(),
            'newsMonth'=>model('News')->whereTime('create_time', 'month')->count(),
            'newsLastMonth'=>model('News')->whereTime('create_time', 'last month')->count(),
            'newsYear'=>model('News')->whereTime('create_time', 'year')->count(),
            'newsLastYear'=>model('News')->whereTime('create_time', 'last year')->count()
        ]);
    }

}
