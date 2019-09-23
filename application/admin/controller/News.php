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
            $news = model('News')->getNewsByCondition($whereData,$catid, $request['from'], $request['size'],$title);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        try{

            // 获取满足条件的数据总数 =》 有多少页
            $total = model('News')->getCountByCondition($whereData,$catid,$title);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        /// 结合总数+size  =》 有多少页
        $pageTotal = ceil($total/$request['size']);//1.1 =>2
        return $this->fetch('', [
            'cats' => model('Cate')->getCateList(),
            'news' => $news,
            'pageTotal' => $pageTotal,
            'curr' => $request['page'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'catid' => empty($data['catid']) ? '' : $data['catid'],
            'title' => empty($data['title']) ? '' : $data['title'],
            'total' => $total,
            'size' => $request['size'],
            'query' => $query,
        ]);
    }
    public function upload(){
        return $this->single('News');
    }
    public function add() {
        parent::add();
        return $this->fetch('', [
            'cats' => model('Cate')->getCateList(),
            'menu'=>Common::getMenu(2)
        ]);
    }
}
