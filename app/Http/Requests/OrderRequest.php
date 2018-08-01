<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends Request
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_id' => ['required',Rule::exists('user_addresses','id')->where('user_id',$this->user()->id)],
            'items' => ['required','array'],
            'items.*.sku_id' => [
                //检查items数组下每一个子数组的sku_id参数
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::query()->find($value)) {
                        $fail('该商品不存在');
                        return;
                    }
                    if (!$sku->product->on_sale) {
                        $fail('该商品未上架');
                        return;
                    }
                    if (!$sku->stock) {
                        $fail('该商品已售空');
                        return;
                    }
                    // 获取当前索引
                    preg_match('/items\.(\d+)\.sku_id/', $attribute, $m);
                    $index = $m[1];
                    $amount = $this->input('items')[$index]['amount'];
                    if ($amount > 0 && $amount>$sku->stock){
                        $fail('该商品库存不足');
                        return;
                    }
                }
            ],
            'items.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }
    public function attributes()
    {
        return ['items'=>'下单产品'];
    }
}
