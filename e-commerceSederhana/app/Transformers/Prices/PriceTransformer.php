<?php

namespace App\Transformers\Prices;

use App\Models\Price;
use League\Fractal\TransformerAbstract;
use App\Transformers\Products\ProductTransformer;

class PriceTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'products'
    ];

    public function transform(Price $price)
    {
        return [
            'id'                => $price->id,
            'pricetable_id'     => $price->priceable_id,
            'pricetable_type'   => $price->priceable_type,
            'user_id'           => $price->user_id,
            'product_id'        => $price->product_id,
            'price'             => $price->price,
            'created'           => $price->created_at->diffForHumans(),
        ];
    }

    public function includeProducts(Price $price)
    {
        $products = $price->products;

        return $this->collection($products, new ProductTransformer);
    }
}