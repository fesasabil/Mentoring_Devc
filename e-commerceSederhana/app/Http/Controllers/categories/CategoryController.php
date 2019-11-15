<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Repositories\Categories\Requests\CreateCategoryRequest;
use App\Repositories\Categories\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->categoryRepo->rootCategories('created_at', 'desc');

        return view('categories.list', [
            'categories' => $this->categoryRepo->paginateArrayResults($list->all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create', [
            'categories' => $this->categoryRepo->listCategories('name', 'asc')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->categoryRepo->createCategory($request->except('__token', '__method'));

        return redirect()->route('categories.index')->with('message', 'Category created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryRepo->findCategoryById($id);

        $data = new CategoryRepository($category);

        return view('categories.show', [
            'category' => $category,
            'categories' => $category->children,
            'product' => $data->findProducts()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('categories.edit', [
            'categories' => $this->categoryRepo->listCategories('name', 'asc', $id),
            'category' => $this->categoryRepo->findCategoryById($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryRepo->findCategoryById($id);

        $update = new CategoryRepository($category);
        $update->updateCategory($request->except('_token', '_method'));

        $request->session()->flash('message', 'Delete successfull');
        return redirect()->route('categories.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $category = $this->categoryRepo->findCategoryById($id);
        $category->product()->sync([]);
        $category->delete();

        request()->session()->flash('message', 'Delete successfull');
        return redirect('categories.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->categoryRepo->deleteFile($request->only('category'));
        request()->session()->flash('message', 'Image removed');
        return redirect('categories.edit', $request->input('category'));
    }
}
