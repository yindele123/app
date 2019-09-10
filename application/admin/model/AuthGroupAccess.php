<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/9 0009
 * Time: 22:11
 */
namespace app\admin\model;
class AuthGroupAccess extends BaseModel{
    protected  $autoWriteTimestamp = false;
    /**
     * 关联用户组表表
     * @return \think\model\relation\HasOne
     */
    public function authGroupAccessFind(){
        return $this->belongsTo('AuthGroup','group_id')->field('id,title');
    }
}