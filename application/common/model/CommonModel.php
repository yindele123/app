<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 11:56
 */
namespace app\common\model;
use think\Model;

class CommonModel extends Model{
    protected  $autoWriteTimestamp = true;

    /**
     * 新增
     * @param $data
     * @return mixed
     */
    public function add($data) {
        if(!is_array($data)) {
            exception('传递数据不合法');
        }
        $this->allowField(true)->save($data);

        return $this->id;
    }

    /**
     * 组装获取子分类
     * @param $cateres  所有分类内容
     * @param $cateid   父ID
     * @return array
     */
    function getColumn($cateres,$cateid){
        static $arr=[];
        foreach ($cateres as $k => $v) {
            if($v['pid'] == $cateid){
                $arr[]=$v['id'];
                $this->getColumn($cateres,$v['id']);
            }
        }
        return $arr;
    }
}