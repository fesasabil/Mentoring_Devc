<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'cover'
    ];

    /**
     * Relation one to many to product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
