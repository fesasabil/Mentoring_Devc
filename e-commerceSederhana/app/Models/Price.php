<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /**
     * Get all of the owning models.
     */
    public function priceable()
    {
        return $this->morphTo();
    }
}
