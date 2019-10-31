<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/10/1 0001
 * Time: 17:49
 */
namespace app\admin\model;
class CateAttach extends BaseModel{
    public function getCateAttachS($where=[],$param=[]){
        $param=$this->setWhereField($param);
        $data=$this->where($where)->order($param['order'])
            ->field('id,cateId,menuValue,showType,image,create_time,status')->paginate($param['size'],false,['page' => $param['page']]);
        return $data;
    }
}