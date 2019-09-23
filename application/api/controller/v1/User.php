<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/23 0023
 * Time: 16:31
 */
namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\service\Token;
use app\api\model\User as UserModel;
use app\common\service\Common;
use app\lib\exception\ErrorException;

class User extends BaseController{
    public function edit(){
        $uid = Token::getCurrentUid();
        $data=request()->post();
        try{
            $update=UserModel::where('id',$uid)->update(['nickname'=>$data['nickname']]);
        }catch (\Exception $e){
            Common::setLog(request()->url().'-----'.$e->getMessage());
            throw new ErrorException(['msg'=>$e->getMessage()]);
        }
        if($update){
            return show(config('code.success'), 'OK', [], 200);
        }
        return show(config('code.error'), 'error', 'no', 200);
    }
}