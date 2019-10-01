<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/6 0006
 * Time: 16:17
 */
namespace app\admin\controller;
use app\common\service\Common;
class Active extends BaseController{
    /**按照版本号app类型统计使用情况
     * @return mixed|string|void
     */
    public function index(){
        $data = input('param.');
        $query = http_build_query($data);
        $request=Common::getPageAndSize($data);
        $whereData=[];
        if(!empty($data['app_type'])){
            $whereData['app_type']=$data['app_type'];
        }
        try{
            $active=model('Active')->getActiveList($whereData,['from'=>$request['from'],'size'=>$request['size']]);
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('active/index'),6,3);
        }
        try{
            $total = model('Active')->getActiveCount($whereData);
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }
        $pageTotal = ceil($total/$request['size']);
        return $this->fetch('',[
            'active'=>$active,
            'query'=> $query,
            'pageTotal' => $pageTotal,
            'curr' => $request['page'],
            'total' => $total,
            'size' => $request['size'],
            'apptype'=>Common::getMenu(1),
            'app_type_value'=>empty($data['app_type']) ? '' : $data['app_type']
        ]);
    }
    /**查看那个版本号那个APP类型的使用情况详情
     * @return mixed|string|void
     */
    public function detailed(){
        $data = input('param.');
        $query = http_build_query($data);
        $request=Common::getPageAndSize($data);
        $whereData=[];
        if(!empty($data['app_type']) && !empty($data['version'])){
            $whereData['app_type']=$data['app_type'];
            $whereData['version']=$data['version'];
        }
        try{
            $active=model('Active')->getActiveDetailedList($whereData,['from'=>$request['from'],'size'=>$request['size']]);
        }catch (\Exception $e){
            return $this->alert($e->getMessage(),url('active/index'),6,3);
        }
        try{
            $total = model('Active')->where($whereData)->count();
        }catch (\Exception $e){
            return $this->result('', config('code.error'), $e->getMessage());
        }

        $pageTotal = ceil($total/$request['size']);
        return $this->fetch('',[
            'active'=>$active,
            'query'=> $query,
            'pageTotal' => $pageTotal,
            'curr' => $request['page'],
            'total' => $total,
            'size' => $request['size'],
            'apptype'=>Common::getMenu(1)
        ]);
    }
}