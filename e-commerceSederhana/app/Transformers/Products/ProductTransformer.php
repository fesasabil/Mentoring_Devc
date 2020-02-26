<?php

namespace App\Transformers\Products;

use App\Models\Product;
use League\Fractal\TransformerAbstract;
use App\Transformers\Categories\CategoryTransformer;
use App\Transformers\Prices\PriceTransformer;

class ProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        // 'categories',
        'prices' 
    ];

    public function transform(Product $product)
    {
        return [
            'id'            => $product->id,
            'name'          => $product->name,
            'image'         => $product->image,
            'category_id'   => $product->category_id,
            'slug'          => $product->slug,
            'description'   => $product->description,
            'created'       => $product->created_at->diffForHumans(),
        ];
    }

    public function includeCategories(Product $product)
    {
        $categories = $product->categories;

        return $this->collection($categories, new CategoryTransformer);
    }

    public function includePrices(Product $product)
    {
        $prices = $product->prices;

        return $this->collection($prices, new PriceTransformer);
    }
}