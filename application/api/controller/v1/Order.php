<?php
/**
 * Created by PhpStorm.
 * User: kezihang
 * Date: 2019/9/21 0021
 * Time: 11:36
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser'],
        'checkSuperScope' => ['only' => 'delivery,getSummary']
    ];

    /**
     * 下单
     * @url /order
     * @HTTP POST
     */
    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = Token::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return show(config('code.success'), 'OK', $status, 200);
    }

    /**
     * 获取订单详情
     * @param $id
     * @return static
     * @throws OrderException
     * @throws \app\lib\exception\ParameterException
     */
    public function getDetail($id)
    {
        $uid = Token::getCurrentUid();
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::where(['id'=>$id,'user_id'=>$uid])->find();
        if (!$orderDetail)
        {
            throw new OrderException();
        }
        return show(config('code.success'), 'OK', $orderDetail->hidden(['prepay_id']), 200);
    }

    /**
     * 根据用户id分页获取订单列表（简要信息）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty())
        {
            $return=[
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
            return show(config('code.success'), 'OK', $return, 200);
        }

        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        $data=[
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
        return show(config('code.success'), 'OK', $data, 200);

    }

    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page=1, $size = 20){
        (new PagingParameter())->goCheck();
//        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        if ($pagingOrders->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        $data=[
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];

        return show(config('code.success'), 'OK', $data, 200);
    }

    public function delivery($id){
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            return show(config('code.success'), 'OK', 'success', 201);
        }
    }
}
