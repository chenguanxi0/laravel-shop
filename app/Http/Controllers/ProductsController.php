<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        //创建一个查询构造器
        $builder = Product::query()->where('on_sale',true);
        //判断是否存在search
        if ($search = $request->input('search','')){
            //存在则进行模糊查询
            $like = '%'.$search.'%';
            $builder->where(function ($query) use ($like){
                $query->where('title','like',$like)
                    ->orwhere('description','like',$like)
                    ->orWhereHas('skus',function ($query) use ($like){
                       $query->where('title','like',$like)
                           ->orwhere('description','like',$like);
                    });
            });
        }
        if ($order = $request->input('order','')){
            //判断是否以 asc desc结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                //判断开头是否为price sold_count rating三个参数
                if (in_array($m[1],['price','sold_count','rating'])){
                    $builder->orderBy($m[1],$m[2]);
                }
            }
        }
        $products = $builder->paginate(16);
        $filters = array('search'=>$search,'order'=>$order);
        return view('products.index',compact('products','filters'));
    }
}
