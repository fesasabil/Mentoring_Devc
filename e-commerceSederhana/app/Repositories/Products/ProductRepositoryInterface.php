<?php

namespace App\Repositories\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use App\Http\Request;
use Jsdecena\Baserepo\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function listProducts();

    public function createProducts(Request $request, Product $product);
}