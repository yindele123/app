<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/2 0002
 * Time: 11:49
 */
namespace app\common\model;
use app\admin\model\BaseModel;

class News extends BaseModel {
    /**
     * 通用化获取参数的数据字段
     */
    public static function getListField($field='') {
        if(empty($field)){
            return [
                'id',
                'catid',
                'image',
                'title',
                'read_count',
                'status',
                'topid',
                'update_time',
                'create_time'
            ];
        }
        return $field;
    }

    /**
     * 根据来获取列表的数据
     * @param int $where  查询条件
     * @param int $catid  查询的分类，多级分类自行组装传进来
     * @param int $from    起始获取值
     * @param int $size    获取多少条数据
     * @param int $title    模糊搜索的新闻标题
     * @param int $field  要查询的字段
     * @param int $order  排序
     * @param array $result  返回按条件获取的新闻列表
     */
//$catid=[], $from=0, $size = 5,$title='',$field='',$order = ['id' => 'desc']
    public function getNewsByCondition($where = [],$param=['']) {
        $param=$this->_setWhereField($param);
        $model=new News;
        if(!empty($param['catid'])){
            $model->where('catid','in',$param['catid']);
        }
        if(!empty($param['title'])) {
            $where['title'] = ['like', '%'.trim($param['catid']).'%'];
        }
        if(!empty($param['topid'])) {
            $model->where( 'FIND_IN_SET(' . $param['topid'] . ',topid)');
        }
        if(!isset($where['status'])) {
            $where['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $result = $model->where($where)->field(self::getListField($param['field']))->order($param['order'])
            ->paginate($param['size'],false,['page' => $param['page']]);
        return $result;
    }

    private function _setWhereField($param=[]){
        if(!isset($param['catid'])) $param['catid']=[];
        if(!isset($param['from'])) $param['from']=0;
        if(!isset($param['size'])) $param['size']=5;
        if(!isset($param['title'])) $param['title']='';
        if(!isset($param['field'])) $param['field']='';
        if(!isset($param['topid'])) $param['topid']='';
        if(!isset($param['page'])) $param['page']=1;
        if(!isset($param['order'])) $param['order']=['id' => 'desc'];
        return $param;
    }
}