<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Shop\Categories\Exceptions\CategoryNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\QueryException;
use Jsdecena\Baserepo\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected $model;
    /**
     * CategoryRepository constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);
        $this->model = $category;
    }

    /**
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return \Illuminate\Support\Collection
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->model->OrderBy($order, $sort)->get()->except($except);
    }

    /**
     * List all root categories
     * 
     * @param  string $order 
     * @param  string $sort  
     * @param  array  $except
     * @return \Illuminate\Support\Collection  
     */
    public function rootCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->model->whereIsRoot()
                        ->OrderBy($order, $sort)
                        ->get()
                        ->except($except);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Category
     * @throws CategoryInvalidArgumentException
     * @throws CategoryNotFoundException
     */
    public function createCategory(array $params): Category
    {
        try {
            $collection = collect($params);
            if (isset($params['name'])) {
                $slug = str_slug($params['name']);
            }

            if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
                $cover = $this->uploadOne($params['cover'], 'categories');
            }

            $merge = $collection->merge(compact('slug', 'cover'));

            $category = new Category($merge->all());

            if (isset($params['parent'])) {
                $parent = $this->findCategoryById($params['parent']);
                $category->$parent()->associate($parent);
            }

            $category->save();
            return $category;

        } catch (QueryException $e) {
            throw new CategoryInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update Category
     * 
     * @param array $params
     * 
     * @return Category
     * 
     * @throws CategoryNotFoundException
     */
    public function updateCategory(array $params): Category
    {
        $category = $this->findCategoryById($this->model->id);
        $collection = collect($params)->except('_token');
        $slug = str_slug($collection->get('name'));

        if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
            $cover = $this->uploadOne($params['cover'], 'categories');
        }

        $merge = $collection->merge(compact('slug', 'cover'));

        // set parent attribute default value if not set
        $params['parent'] = $params['parent'] ?? 0;

        // If parent category is not set on update
        // just make current category as root
        // else we need to find the parent
        // and associate it as child
        if ( (int)$params['parent'] == 0) {
            $category->saveAsRoot();
        } else {
            $parent = $this->findCategoryById($params['parent']);
            $category->parent()->associate($parent);
        }

        $category->update($merge->all());

        return $category;
    }

    /**
     * @param int $id
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function findCategoryById(int $id): Category
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException($e->getMessage());
        }
    }

    /**
     * Delete Category
     * 
     * @return bool
     * 
     * @throws Exception
     */
    public function deleteCategory(): bool
    {
        return $this->model->delete();
    }

    /**
     * Associate a product in a category
     *
     * @param Product $product
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function associateProduct(Product $product)
    {
        return $this->model->product()->save($product);
    }

    /**
     * Return all the product associated with the category
     *
     * @return mixed
     */
    public function findProducts(): Collection
    {
        return $this->model->product;
    }

    // /**
    //  * @param array $params
    //  */
    // public function syncProducts(array $params)
    // {
    //     $this->model->product()->sync($params);
    // }

    //  /**
    //  * Detach the association of the product
    //  *
    //  */
    // public function detachProducts()
    // {
    //     $this->model->product()->detach();
    // }


}