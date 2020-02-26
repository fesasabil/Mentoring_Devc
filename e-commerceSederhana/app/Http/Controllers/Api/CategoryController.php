<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Transformers\Categories\CategoryTransformer;
// use App\Repositories\Categories\Requests\CreateCategoryRequest;
// use App\Repositories\Categories\CategoryRepositoryInterface;
// use App\Repositories\Categories\CategoryRepository;

class CategoryController extends Controller
{
    public function createCategory(Request $request, Category $category)
    {
        $this->validate($request, [
            'name'        => 'required',
            'description' => 'required',
            'cover'       => 'required'
        ]);

        $category = $category->create([
            'name'        => $request->name,
            'description' => $request->description,
            'slug'        => str_slug($request->name),
            'cover'       => $request->cover
        ]);

        $response = fractal()
            ->item($category)
            ->transformWith(new CategoryTransformer)
            ->toArray();
        
        return response()->json($response, 201);
    }

    public function showCategory(Category $category)
    {
        $category = $category->all();

        $response = fractal()
            ->collection($category)
            ->transformWith(new CategoryTransformer)
            ->includeProducts()
            ->toArray();

        return response()->json($response, 200);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $category->name         = $request->get('name', $category->name);
        $category->description  = $request->get('description', $category->description);
        $category->cover        = $request->get('cover', $category->cover);

        $category->save();

        $response = fractal()
            ->item($category)
            ->transformWith(new CategoryTransformer)
            ->toArray();
        
        return response()->json($response, 200);
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted',
        ]);
    }
    // /**
    //  * @var CategoryRepositoryInterface
    //  */
    // private $categoryRepo;

    // /**
    //  * CategoryController constructor.
    //  *
    //  * @param CategoryRepositoryInterface $categoryRepository
    //  */
    // public function __construct(CategoryRepositoryInterface $categoryRepository)
    // {
    //     $this->categoryRepo = $categoryRepository;
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     $list = $this->categoryRepo->rootCategories('created_at', 'desc');

    //     return view('categories.list', [
    //         'categories' => $this->categoryRepo->paginateArrayResults($list->all())
    //     ]);
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     return view('categories.create', [
    //         'categories' => $this->categoryRepo->listCategories('name', 'asc')
    //     ]);
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  CreateCategoryRequest  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(CreateCategoryRequest $request)
    // {
    //     $this->categoryRepo->createCategory($request->except('__token', '__method'));

    //     return redirect()->route('categories.index')->with('message', 'Category created');
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     $category = $this->categoryRepo->findCategoryById($id);

    //     $data = new CategoryRepository($category);

    //     return view('categories.show', [
    //         'category' => $category,
    //         'categories' => $category->children,
    //         'product' => $data->findProducts()
    //     ]);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     return view('categories.edit', [
    //         'categories' => $this->categoryRepo->listCategories('name', 'asc', $id),
    //         'category' => $this->categoryRepo->findCategoryById($id),
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  UpdateCategoryRequest $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(UpdateCategoryRequest $request, $id)
    // {
    //     $category = $this->categoryRepo->findCategoryById($id);

    //     $update = new CategoryRepository($category);
    //     $update->updateCategory($request->except('_token', '_method'));

    //     $request->session()->flash('message', 'Delete successfull');
    //     return redirect()->route('categories.edit', $id);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(int $id)
    // {
    //     $category = $this->categoryRepo->findCategoryById($id);
    //     $category->product()->sync([]);
    //     $category->delete();

    //     request()->session()->flash('message', 'Delete successfull');
    //     return redirect('categories.index');
    // }

    // /**
    //  * @param Request $request
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    // public function removeImage(Request $request)
    // {
    //     $this->categoryRepo->deleteFile($request->only('category'));
    //     request()->session()->flash('message', 'Image removed');
    //     return redirect('categories.edit', $request->input('category'));
    // }
}
