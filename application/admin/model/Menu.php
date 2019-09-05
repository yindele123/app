<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/5 0005
 * Time: 16:40
 */
namespace app\admin\model;
class Menu extends BaseModel{
    /**
     * 获取递归分类
     */
    public function menutree(){
        $where['status'] = [
            'neq', config('code.status_delete')
        ];
        $cateres=$this->order('sort desc')->where($where)->field('id,name,pid,value,sort,status')->select();
        return collection($this->sort($cateres))->toArray();
    }

    /**
     * 获取所有子分类
     * @param $cateid 父ID
     * @return array
     */
    public function getchilmenu($cateid){
        $cateres=$this->select();
        return $this->getColumn($cateres,$cateid);
    }
}