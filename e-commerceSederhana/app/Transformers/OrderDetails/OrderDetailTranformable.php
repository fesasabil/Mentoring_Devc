<?php

namespace App\Transformers\OrderDetails;

use App\Models\Order;
use App\Repositories\Orders\OrderRepository;
use App\Models\Product;
use App\Repositories\Products\ProductRepository;
use App\Models\OrderDetail;
use App\Repositories\OrderDetails\OrderDetailRepository;

trait OrderDetailTransformable
{
    protected function transformOrderDetail(OrderDetail $order_detail): OrderDetail
    {
        $orderRepo = new OrderRepository(new Order());
        $order_detail->order = $orderRepo->findOrderById($order_detail->order_id);

        $productRepo = new ProductRepository(new Product());
        $order_detail->product = $productRepo->findProductById($order_detail->product_id);

        // $orderDetailRepo = new OrderDetailRepository(new OrderDetail());

        return $order_detail;
    }
}
