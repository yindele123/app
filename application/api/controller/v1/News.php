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
use app\lib\exception\ErrorException;
use app\lib\exception\MissException;

class News extends BaseController{
    public function index() {
        $data = input('get.');
        $catid=!empty($data['catid']) ? $data['catid'] : 0;
        $title=!empty($data['title']) ? $data['title'] : '';
        $whereData = [];
        $whereData[] = isset($data['is_position']) ? ['is_position' => $data['is_position']] : '';
        $whereData = Common::setWhere($whereData);
        $recovery=Common::getPageAndSize($data);
        if(!empty($catid)){
            $common=new Common();
            $common->model='Cate';
            $common->usuallyId($catid,'没有该分类ID，请输入正常的分类ID',config('cacheName.api_cate_id'),config('cacheName.api_cate_id'));
        }
        try{
            $total = model('News')->getNewsCount(config('cacheName.api_news_list_count'),config('cacheTiem.api_news_list_count'),$whereData,$catid,$title);
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException(['msg'=>$e->getMessage()]);
        }
        try{
            $news = model('News')->getNewsList(config('cacheName.api_news_list'),config('cacheTiem.api_news_list'),$whereData,$catid, $recovery['from'], $recovery['size'],$title);
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException(['msg'=>$e->getMessage()]);
        }
        $news=Common::isNumeric($news);
        $result = [
            'total' => $total,
            'page_num' => ceil($total / $recovery['size']),
            'list' => $news,
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
        $field=[
            'id',
            'catid',
            'image',
            'title',
            'status',
            'create_time',
            'description',
            'content'
        ];
        try{
            $news = model('News')->getNewsFind(config('cacheName.api_news_find'),config('cacheTiem.api_news_find'),$id,$field);
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
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
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
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
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }

        return show(config('code.success'), 'OK', $rands, 200);
    }
    //获取推荐新闻
    public function recommend(){
        $catid=input('get.catid',0,'intval');
        $size=Common::setCount(input('get.size',0,'intval'));
        try{
            $data=model('News')->getPositionNormalNews($catid,$size);
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException();
        }
        return show(config('code.success'), 'OK', $data, 200);
    }
}