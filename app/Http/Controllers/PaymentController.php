<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * payByAlipay
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/8/3
     * @throws
     */
    public function payByAlipay(Order $order,Request $request)
    {
        $this->authorize('own', $order);
        if ($order->closed || $order->paid_at){
            throw new InvalidRequestException('订单状态不正确');
        }
        // 调用支付宝的网页支付
        return app('alipay')->web([
            'out_trade_no' => $order->no, // 订单编号，需保证在商户端不重复
            'total_amount' => $order->total_amount, // 订单金额，单位元，支持小数点后两位
            'subject'      => '支付 Laravel Shop 的订单：'.$order->no, // 订单标题
        ]);
    }
    public function alipayReturn()
    {
        try{
            app('alipay')->verify();
        }catch (\Exception $e){
            return view('page.error',['msg'=>'数据不正确 ']);
        }
        return view('pages.success', ['msg' => '付款成功']);
    }
    public function alipayNotify()
    {
        $data = app('alipay')->verify();
        $order = Order::query()->where('no',$data->out_trade_no)->first();
        if (!$order) {
            return 'fail';
        }
        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at) {
            // 返回数据给支付宝
            return app('alipay')->success();
        }
        $order->update([
            'paid_at'        => Carbon::now(), // 支付时间
            'payment_method' => 'alipay', // 支付方式
            'payment_no'     => $data->trade_no, // 支付宝订单号
        ]);

        return app('alipay')->success();
    }
}
