<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:50
 */
namespace app\api\model;
class Auth extends BaseModel
{
    public function hi()
    {
        return $this->belongsToMany('User','user_auth','auth_id', 'user_id');
    }
}
