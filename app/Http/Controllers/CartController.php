<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();
        return view('cart.index',compact('cartItems'));
    }
    public function add(AddCartRequest $request)
    {
        $user   = $request->user();
        $skuId  = $request->input('sku_id');
        $amount = $request->input('amount');
        $cart = $user->cartItems()->where('product_sku_id', $skuId)->first();

        if ($cart){
            //如果已经存在此商品 直接添加
            $cart->update([
                'amount' => $cart->amount + $amount
            ]);
            return [];
        }
        //如果不存在 则直接创建新的购物车对象
        $cartItem = new CartItem(['amount'=>$amount]);
        $cartItem->user()->associate($user);
        $cartItem->productSku()->associate($skuId);
        $cartItem->save();
        return [];
    }

    public function remove(ProductSku $productSku, Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id', $productSku->id)->delete();
        return [];
    }
}
