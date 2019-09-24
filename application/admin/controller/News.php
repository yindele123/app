<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/30 0030
 * Time: 21:33
 */
namespace app\admin\controller;
use app\common\service\Common;
class News extends BaseController
{
    public function index() {
        $data = input('param.');
        $catid=[];
        $title=!empty($data['title']) ? $data['title'] : '';
        $topid=!empty($data['topid']) ? $data['topid'] : '';
        $query = http_build_query($data);
        $whereData = setCheckTime($data);
        //die();
        if (!empty($data['catid'])){
            $catid=model('Cate')->getchilrenid($data['catid']);
            $catid[]=$data['catid'];
        }
        $request=Common::getPageAndSize($data);
        // 获取表里面的数据
        try{
            $news = model('News')->getNewsByCondition($whereData,['catid'=>$catid,'topid'=>$topid,'page'=>$request['page'],'title'=>$title,'size'=>$request['size']]);
        }catch (\Exception $e){
            echo model('News')->getLastSql();
            return $this->result('', config('code.error'), $e->getMessage());
        }
        return $this->fetch('', [
            'cats' => model('Cate')->getCateList(),
            'news' => $news,
            'curr' => $request['page'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'catid' => empty($data['catid']) ? '' : $data['catid'],
            'topidV' => empty($data['topid']) ? '' : $data['topid'],
            'title' => empty($data['title']) ? '' : $data['title'],
            'total' => $news->total(),
            'size' => $request['size'],
            'query' => $query,
            'topid'=>Common::getMenu(24)
        ]);
    }
    public function upload(){
        return $this->single('News');
    }
    public function add() {
        parent::add();
        return $this->fetch('info', [
            'cats' => model('Cate')->getCateList(),
            'menu'=>Common::getMenu(2),
            'topid'=>Common::getMenu(24),
            'action'=>'add'
        ]);
    }
    public function edit()
    {
        parent::edit();
        $id=input('param.id');
        $news=$this->usuallyId($id);
        return $this->fetch('info',[
            'data'=>$news,
            'cats' => model('Cate')->getCateList(),
            'menu'=>Common::getMenu(2),
            'topid'=>Common::getMenu(24),
            'action'=>'edit'
        ]);
    }
}
