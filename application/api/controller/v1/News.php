<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 12:49
 */
namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\common\service\Common;
use app\common\model\News as CommonNews;
use app\lib\exception\ErrorException;
use app\lib\exception\MissException;

class News extends BaseController{
    public function index() {
        // 小伙伴仿照我们之前讲解的validate验证机制 去做相关校验
        $data = input('get.');

        $whereData['status'] = config('code.status_normal');
        if(!empty($data['catid'])) {
            $whereData['catid'] = input('get.catid', 0, 'intval');
        }
        if(!empty($data['title'])) {
            $whereData['title'] = ['like', '%'.$data['title'].'%'];
        }

        $recovery=Common::getPageAndSize($data);
        try{
            $total = (new CommonNews())->getNewsCountByCondition($whereData);
        }catch (\Exception $e){
            throw new  ErrorException();
        }
        try{
            $news = (new CommonNews())->getNewsByCondition($whereData, $recovery['from'], $recovery['size']);
        }catch (\Exception $e){
            throw new  ErrorException();
        }
        $result = [
            'total' => $total,
            'page_num' => ceil($total / $recovery['size']),
            'list' => $this->getDealNews($news),
        ];

        return show(config('code.success'), 'OK', $result, 200);
    }

    /**
     * 获取详情接口
     */
    public function read() {
        $id=input('get.id','','intval');
        (new IDMustBePositiveInt())->goCheck();
        // 通过id 去获取数据表里面的数据
        try{
            $news = (new CommonNews())->getNewsFind($id);
        }catch (\Exception $e){
            throw new  ErrorException();
        }
        if(empty($news) || $news->status != config('code.status_normal')) {
            throw new MissException([
                'msg' => '请求新闻不存在',
                'errorCode' => 40000
            ]);
        }
        try {
            model('News')->where(['id' => $id])->setInc('read_count');
        }catch(\Exception $e) {
            throw new  ErrorException();
        }
        $cats = config('cat.lists');
        $news->catname = $cats[$news->catid];
        return show(config('code.success'), 'OK', $news, 200);
    }

    /**
     * 获取排行榜数据列表
     * 1、获取数据库 然 read_count排序  5 - 10
     * 2、优化 redis
     */
    public function rank() {
        try {
            $rands = model('News')->getRankNormalNews();
            $rands = $this->getDealNews($rands);
        }catch (\Exception $e) {
            throw new  ErrorException();
        }

        return show(config('code.success'), 'OK', $rands, 200);
    }

}