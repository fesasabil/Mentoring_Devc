<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Price;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Transformers\Prices\PriceTransformer;
use App\Transformers\Products\ProductTransformer;
use Illuminate\Database\Eloquent\Builder;
// use App\Repositories\Products\ProductRepository;
// use App\Repositories\Products\ProductRepositoryInterface;

class ProductController extends Controller
{
    // private $productRepo;

    // public function __construct(ProductRepositoryInterface $productRepository)
    // {
    //     $this->productRepo = $productRepository;
    // }

    // public function create()
    // {

    // }
    public function createProducts(Request $request, Product $product)
    {
        $this->validate($request, [
            'name'          => 'required|max:100',
            // 'image'         => 'mimes:jpg,png,jpeg',
            'description'   => 'required',
        ]);

        $product = $product->create([
            'name'          => $request->name,
            'image'         => $request->image,
            'category_id'   => $request->category_id,
            'slug'          => str_slug($request->name),
            'description'   => $request->description,
        ]);

        $response = fractal()
            ->item($product)
            ->transformWith(new ProductTransformer)
            ->toArray();

        return response()->json($response, 201);
    }

    public function showProducts(Product $product)
    {
        $product = $product->all();

        $response = fractal()
            ->collection($product)
            ->transformWith(new ProductTransformer)
            ->includeCategories()
            ->toArray();

        return response()->json($response, 200);
    }

    public function update(Request $request, Product $product)
    {
        $product->name          = $request->get('name', $product->name);
        $product->image         = $request->get('image', $product->image);
        $product->description   = $request->get('description', $product->description);

        $product->save();

        $response = fractal()
            ->item($product)
            ->transformWith(new ProductTransformer)
            ->toArray();
        
        return response()->json($response, 200);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
        ]);
    }

    public function detailProduct(Product $product)
    {
        $products = $product->all();
        

        $response = fractal()
            ->collection($products)
            ->transformWith(new ProductTransformer)
            ->includePrices()
            // ->includeCategories()
            ->toArray();

        return response()->json($response, 200);

    }
}
