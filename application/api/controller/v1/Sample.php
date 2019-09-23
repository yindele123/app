<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:42
 */
namespace app\api\controller\v1;

use app\api\model\Auth;
use app\api\model\Product;
use app\api\validate\SampleGet;
use app\lib\exception\MissException;
use think\Controller;
use app\api\service\Sample as SampleService;
use think\Request;

/*
 * Resource Sample
 */

class Sample extends Controller
{

    /**
     * Sample 样例
     * @url     /sample/:key
     * @http    get
     * @param   int $key 键
     * @return  array of values , code 200
     * @throws  MissException
     */
    public function getSample($key)
    {
        $validate = new SampleGet();
        $validate->goCheck();
        $key = (int)$key;
        $result = SampleService::getSampleByKey($key);
        if (empty($result)) {
            throw new MissException([
                'msg' => 'sample not found'
            ]);
        }
        return show(config('code.success'), 'OK', $result, 200);
    }

    public function test1()
    {
        $users = Auth::with(['hi' => function ($query) {
            $query->where('id', '>', 2);
        }])
            ->find(1);
        return show(config('code.success'), 'OK', $users, 200);
    }

    public function test2($id=1)
    {
        $n = input('param.');
        Request::instance()->get(['name'=>10]);
        echo input('get.name');
    }
}
