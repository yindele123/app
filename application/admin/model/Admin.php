<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/8/28 0028
 * Time: 15:25
 */
namespace app\admin\model;
use app\lib\exception\ParameterException;

class Admin extends BaseModel {
    protected $autoWriteTimestamp = true;

    /**
     * 根据来获取列表的数据
     * @param int $where  查询条件
     * @param int $from    起始获取值
     * @param int $size    获取多少条数据
     * @param int $search    模糊搜索的管理员(用户名)
     * @param int $field  要查询的字段
     * @param int $order  排序
     * @param array $result  返回按条件获取的管理员列表
     */
    public function getAdmin($where = [], $from=0, $size = 5,$search='',$field='',$order = ['id' => 'desc']){
        $model=new Admin;
        if(!empty($search)) {
            $model->where('username', 'like', '%' . trim($search) . '%');
        }
        if(!isset($where['status'])) {
            $where['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $result = $model->where($where)->with(['authGroupAccessFind','authGroupAccessFind.authGroupAccessFind'])->limit($from, $size)->field(self::getListField($field))->order($order)->select();
        return collection($result)->toArray();
    }
    /**
     * 根据来获取管理员总数
     * @param int $where  查询条件
     * @param int $search    模糊搜索的管理员(用户名)
     * @param int $result
     */
    public function adminCount($where = [],$search=''){
        $model=new Admin;
        if(!empty($search)) {
            $model->where('username', 'like', '%' . trim($search) . '%');
        }
        if(!isset($where['status'])) {
            $where['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $data=$model->where($where)->count();
        return $data;
    }

    /***
     * 根治用户名获取单个用户
     * @param $username  用户名
     * @param array $field  获取的字段
     */
    public function getUser($username,$field=[]){
        if(empty($username)){
            throw new ParameterException([
                'msg'=>'请不要非法操作'
            ]);
        }
        $user=$this->where(['username'=>$username])->with(['authGroupAccessFind','authGroupAccessFind.authGroupAccessFind'])->field(self::getListField($field))->find();
        if(empty($user)){
            return false;
        }
        return $user;
    }

    /**
     * 管理获取用户组明细表
     * @return \think\model\relation\HasOne
     */
    public function authGroupAccessFind(){
        return $this->hasOne('AuthGroupAccess','uid');
    }
    /***
     * 根治ID一对一换取用户跟用户组明细表
     * @param $id 用户ID
     * @param array $field 获取的字段
     * @throws array $data
     */
    public function getAdminGroup($id,$field=[]){
        $data=$this->where(['id'=>$id])->with('authGroupAccessFind')->field(self::getListField($field))->find()->toArray();
        return $data;
    }
    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'username',
                'mobile',
                'create_time',
                'status',
                'password',
                'pwd_key'
            ];
        }
        return $field;
    }
}