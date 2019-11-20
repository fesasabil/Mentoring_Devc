<?php

namespace App\Repositories\Orders;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function createOrder(array $data): Order;

    public function updateOrder(array $params): bool;

    public function findOrderById(int $id): Order;

    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function findProduct(Order $order): Collection;

    public function associateProduct(Product $product, int $quantity = 1, array $data = []);

    public function searchOrder(String $text): Collection;
}