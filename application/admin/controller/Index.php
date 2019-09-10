<?php
namespace app\admin\controller;


use app\common\service\Common;
use think\Request;

class Index extends BaseController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('user',$this->user);
    }

    public function index()
    {
        return $this->fetch('index');
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
