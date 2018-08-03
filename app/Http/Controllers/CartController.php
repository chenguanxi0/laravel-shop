<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use App\repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartRepository ;
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index(Request $request)
    {

        $cartItems = $this->cartRepository->get();
        $addresses = $request->user()->addresses()->orderBy('last_used_at','desc')->get();
        return view('cart.index',compact('cartItems','addresses'));
    }
    public function add(AddCartRequest $request)
    {
        $this->cartRepository->add($request->input('sku_id'),$request->input('amount'));
        return [];
    }

    public function remove(ProductSku $productSku)
    {
        $this->cartRepository->remove($productSku->id);
        return [];
    }
}
