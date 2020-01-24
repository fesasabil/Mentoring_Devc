<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Transformers\Products\ProductTransformer;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Products\ProductRepositoryInterface;

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
            'name'          => 'required|min:8',
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
            // ->includeCategory()
            ->toArray();

        return response()->json($response, 201);
    }

    public function showProducts(Product $product)
    {
        $product = $product->all();

        $response = fractal()
            ->collection($product)
            ->transformWith(new ProductTransformer)
            ->toArray();

        return response()->json($response, 200);
    }

    public function update(Request $request, Product $product)
    {
        $product->name          = $request->get('name', $product->name);
        $product->image         = $request->get('image', $product->image);
        $product->description   = $request->get('description', $product->description);

        $product->save();

        return fractal()
            ->item($product)
            ->transformWith(new ProductTransformer)
            ->toArray();
    }

    public function delete(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
        ]);
    }
}
