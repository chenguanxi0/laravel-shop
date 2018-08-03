<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\repositories\OrderRepository;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $orders = Order::query()
            ->with(['orderItems.product', 'orderItems.productSku'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();
        return view('orders.index', compact('orders'));
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
        $address = $user->addresses()->find($request->input('address_id'));
        $remark = $request->input('remark');
        $items = $request->input('items');
        return $this->orderRepository->createOrder($user, $address, $remark, $items);
    }

    /**
     * show
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/8/2
     * @throws
     */
    public function show(Order $order)
    {
        $this->authorize('own', $order);
        $order = $order->load(['orderItems.productSku', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

}
