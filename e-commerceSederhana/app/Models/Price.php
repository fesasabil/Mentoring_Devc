<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Price extends Model
{
    protected $fillable = [
        'price', 'user_id', 'product_id'
    ];
    /**
     * Get all of the owning models.
     */
    public function priceable()
    {
        return $this->morphTo();
    }
}
