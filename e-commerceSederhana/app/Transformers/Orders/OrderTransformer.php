<?php

namespace App\Transformers\Orders;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'id'        => $order->id,
            'invoice'   => $order->invoice,
            'discounts' => $order->discounts,
            'total'     => $order->total
        ];
    }
}
