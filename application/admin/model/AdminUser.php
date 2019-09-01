<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 15:25
 */
namespace app\admin\model;
use app\lib\exception\ParameterException;

class AdminUser extends BaseModel {
    protected $autoWriteTimestamp = true;

    /**
     * @param $data 要添加的数据
     * @throws ParameterException
     * @return $id 添加ID号
     */
    public function add($data){
        if(!is_array($data) || empty($data)){
            throw new ParameterException([
                'msg'=>'请不要非法操作'
            ]);
        }
        $this->allowField(true)->save($data);
        return $this->id;
    }

    public function getUser($username){
        if(empty($username)){
            throw new ParameterException([
                'msg'=>'请不要非法操作'
            ]);
        }
        $user=$this::get(['username'=>$username]);
        if(empty($user)){
            return false;
        }
        return empty($user) ? false : $user->hidden(['listorder','remarks','create_time','update_time','delete_time'])->toArray();
    }
}