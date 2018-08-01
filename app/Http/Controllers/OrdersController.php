<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\repositories\OrderRepository;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * store
     * @throws
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/31
     */
    public function store(OrderRequest $request)
    {
        $user = $request->user();
        // 开启一个数据库事务
        $order = $this->orderRepository->createOrder($user,$request);
        $this->dispatch(new CloseOrder($order,config('app.order_ttl')));
        return $order;
    }

}
