<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31
 * Time: 17:19
 */

namespace App\repositories;

use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Models\UserAddress;
use Carbon\Carbon;

class OrderRepository
{
    /**
     * createOrder
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/31
     * @throws
     */
    public function createOrder(User $user,UserAddress $address,$remark,$items)
    {

        $order = \DB::transaction(function () use($user,$address,$remark,$items) {
            // 更新此地址的最后使用时间

            $address->update(['last_used_at'=>Carbon::now()]);
            // 创建一个订单
            $order = new Order([
                'address'=>[
                    'address' => $address->full_address,
                    'zip' => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark' => $remark,
                'total_amount' => 0,
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;
            // 遍历用户提交的 SKU
            foreach ($items as $data){
                // 创建一个 OrderItem 并直接与当前订单关联
                $sku = ProductSku::query()->find($data['sku_id']);
                $item = $order->orderItems()->make([
                    'amount'=>$data['amount'],
                    'price'=>$sku->price
                ]);

                    $item->product()->associate($sku->product_id);
                    $item->productSku()->associate($sku);
                    $item->save();

                $totalAmount += $sku->price*$data['amount'];
                if ($sku->decreaseStock($data['amount'])<=0){
                    throw new InvalidRequestException('该商品库存不足');
                }
            }
            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);
            // 将下单的商品从购物车中移除
            $product_sku_id = collect($items)->pluck('sku_id');
            app(CartRepository::class)->remove($product_sku_id);
            return $order;
        });
        dispatch(new CloseOrder($order,config('app.order_ttl')));
        return $order;
    }


}