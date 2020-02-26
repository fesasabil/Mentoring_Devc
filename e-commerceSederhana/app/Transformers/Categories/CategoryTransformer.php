<?php

namespace App\Transformers\Categories;

use App\Models\Category;
use App\Transformers\Products\ProductTransformer;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'products'
    ];

    public function transform(Category $category)
    {
        return [
            'id'            => $category->id,
            'name'          => $category->name,
            'description'   => $category->description,
            'cover'         => $category->cover,
            'slug'          => $category->slug,
            'created'       => $category->created_at->diffForHumans(),
        ];
    }

    public function includeProducts(Category $category)
    {
        $products = $category->products;

        return $this->collection($products, new ProductTransformer);
    }
}