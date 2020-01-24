<?php

namespace App\Repositories\Products;

use App\Models\Product;
use App\Models\Category;
use Jsdecena\Baserepo\BaseRepository;
use App\Repositories\Products\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use App\Http\Request;
use App\Transformers\Products\ProductTransformer;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;
    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        // parent::__construct($product);
        $this->model = $product;
    }

    /**
     * List all the Product
     */
    public function listProducts()
    {

    }

    public function createProducts(Request $request, Product $product)
    {
        $this->validate($request, [
            'name'          => 'required|min:8',
            'image'         => 'mimes:jpg,png,jpeg',
            'description'   => 'required',
        ]);

        $product = $product->create([
            'name'          => $request->name,
            'image'         => $request->image,
            'category_id'   => $request->category()->id,
            'slug'          => $request->str_slug(),
            'description'   => $request->description,
        ]);

        $response = fractal()
            ->item($product)
            ->transformWith(new ProductTransformer)
            ->toArray();

        return response()->json($response, 201);
    } 
    
}