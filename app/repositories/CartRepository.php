<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/3
 * Time: 9:28
 */

namespace App\repositories;


use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartRepository
{
    public function get()
    {
        $cartItems = Auth::user()->cartItems()->with(['productSku.product'])->get();
        return $cartItems;
    }
    public function add($skuId,$amount)
    {
        $user   = Auth::user();
        $cart = $user->cartItems()->where('product_sku_id', $skuId)->first();

        if ($cart){
            //如果已经存在此商品 直接添加
            $cart->update([
                'amount' => $cart->amount + $amount
            ]);
            return $cart;
        }
        //如果不存在 则直接创建新的购物车对象
        $cartItem = new CartItem(['amount'=>$amount]);
        $cartItem->user()->associate($user);
        $cartItem->productSku()->associate($skuId);
        $cartItem->save();
        return $cart;
    }
    public function remove($skuIds)
    {
        if (!is_array($skuIds)){
            $skuIds = [$skuIds];
        }
        Auth::user()->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
    }
}