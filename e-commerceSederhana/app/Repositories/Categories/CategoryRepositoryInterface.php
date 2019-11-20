<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Jsdecena\Baserepo\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection;

    public function createCategory(array $params): Category;

    public function updateCategory(array $params): Category;

    public function findCategoryById(int $id): Category;

    public function deleteCategory(): bool;

    public function associateProduct(Product $product);

    public function rootCategories(string $string, string $string1);

}