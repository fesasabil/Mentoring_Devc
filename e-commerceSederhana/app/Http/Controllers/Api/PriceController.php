<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Transformers\Prices\PriceTransformer;

class PriceController extends Controller
{
    public function showPrice(Price $price)
    {
        $prices = $price->all();

        $response = fractal()
            ->collection($prices)
            ->transformWith(new PriceTransformer)
            // ->includeProducts()
            ->toArray();

        return response()->json($response, 200);
    }
}
