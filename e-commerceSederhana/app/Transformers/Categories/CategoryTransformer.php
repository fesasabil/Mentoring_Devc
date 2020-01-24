<?php

namespace App\Transformers\Categories;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id'            => $category->id,
            'name'          => $category->name,
            'description'   => $category->description,
            'cover'         => $category->cover,
            'created'       => $category->created_at->diffForHumans(),
        ];
    }
}