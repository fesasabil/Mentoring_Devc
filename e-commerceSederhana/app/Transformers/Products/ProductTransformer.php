<?php

namespace App\Transformers\Products;

use App\Models\Product;
use League\Fractal\TransformerAbstract;
use App\Transformers\Categories\CategoryTransformer;

class ProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'category'
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
        $category = $product->category;

        return $this->collection($category, new CategoryTransformer);
    }
}