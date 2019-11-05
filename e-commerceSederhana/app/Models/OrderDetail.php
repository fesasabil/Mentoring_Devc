<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Price;
use App\Models\Product;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'qty', 'weight'
    ];

    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
