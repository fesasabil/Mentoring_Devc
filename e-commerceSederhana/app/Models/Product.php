<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Price;
use App\Models\OrderDetail;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'description',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);      
    }
}
