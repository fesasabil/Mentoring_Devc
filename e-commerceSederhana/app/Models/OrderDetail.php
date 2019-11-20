<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Price;
use App\Models\Product;
use Nicolaslopezj\Searchable\SearchableTrait;

class OrderDetail extends Model
{
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * Columns and their priority in search results.
     * Columns with higher values are more important.
     * Columns with equal values have equal importance.
     *
     * @var array
     */

    protected $searchable = [
        'columns' => [
            'products.name' => 10,
            'orders.reference' => 8,
        ],
        'joins' => [
            'order_product' => ['orders.id', 'order_product.order_id'],
            'products' => ['products.id', 'order_details.product_id'],
        ],
        'groupBy' => [
            'orderDetails.id'
        ]
    ];

    protected $fillable = [
        'order_id', 'product_id', 'qty', 'weight'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
