<?php

namespace App\Models;

use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $fillable = ['title', 'description', 'price', 'stock'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    /**
     * decreaseStock 减少库存
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/31
     * @throws
     */
    public function decreaseStock($amount)
    {
        if ($amount < 0){
            throw new InternalException('减库存不可小雨0');
        }
        return $this->newQuery()->where('id',$this->id)->where('stock','>=',$amount)->decrement('stock',$amount);
    }

    /**
     * addStock
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/31
     * @throws
     */
    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }
        $this->increment('stock', $amount);
    }
}
