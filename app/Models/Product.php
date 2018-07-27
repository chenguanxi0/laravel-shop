<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price'
    ];
    protected $casts = [
        'on_sale' => 'boolean'
    ];
    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }
    public function getImageUrlAttribute()
    {
        if (Str::startsWith($this->image,['http://','https://'])){
            return $this->image;
        }
        return \Storage::disk('public')->url($this->image) ;
    }
}
