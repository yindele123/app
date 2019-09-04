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
        $catid=!empty($data['catid']) ? $data['catid'] : 0;
        $title=!empty($data['title']) ? $data['title'] : '';
        $query = http_build_query($data);
        $whereData = [];
        // 转换查询条件
        if(!empty($data['start_time']) && !empty($data['end_time'])
            && $data['end_time'] > $data['start_time']
        ) {
            $whereData['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
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
            $total = model('News')->getNewsCountByCondition($whereData,$catid,$title);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        /// 结合总数+size  =》 有多少页
        $pageTotal = ceil($total/$request['size']);//1.1 =>2
        return $this->fetch('', [
            'cats' => config('cat.lists'),
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

    public function add() {

        if(request()->isAjax()) {
            $data = input('post.');
            $this->validateCheck($data);
            //入库操作
            try {
                $id = model('News')->add($data);
            }catch (\Exception $e) {
                return $this->result('', config('code.error'), '新增失败');
            }
            if($id) {
                return $this->result(['jump_url' => url('news/index')], config('code.success'), 'OK');
            } else {
                return $this->result('', config('code.error'), '新增失败');
            }
        }else {
            return $this->fetch('', [
                'cats' => config('cat.lists')
            ]);
        }
    }
}
