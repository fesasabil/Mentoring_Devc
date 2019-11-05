<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\orderDetail;

class Order extends Model
{
    protected $fillable = [
        'invoice', 'discounts', 'total'
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
