<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CloseOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order,$delay)
    {
        $this->order = $order;
        $this->delay($delay);
    }

    /**
     * Execute the job.
     * @throws
     * @return void
     */
    public function handle()
    {
        //先判断是否已经支付
        if($this->order->paid_at){
            return;
        }
        //如果没有支付 则关闭订单 同时将库存还原
        \DB::transaction(function (){
            $this->order->update(['closed' => true]);
            foreach ($this->order->orderItems as $item){
                $amount = $item->amount;
                $item->productSku->addStock($amount);
            }
        });
    }
}
