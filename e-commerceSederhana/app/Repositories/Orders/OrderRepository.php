<?php

namespace App\Repositories\Orders;

use App\Events\OrderCreateEvent;
use App\Mail\sendEmailNotificationToAdminMailable;
use App\Mail\SendOrderToCustomerMailable;
use Jsdecena\Baserepo\BaseRepository;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Orders\OrderNotFoundException;
use App\Repositories\Orders\OrderRepositoryInterface;
use Illuminate\Database\QueryException;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $model;
    /**
     * OrderRepository constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
        $this->model = $order;
    }

    /**
     * Create the order
     *
     * @param array $params
     * @return Order
     * @throws OrderInvalidArgumentException
     */
    public function createOrder(array $params): Order
    {
        try {
            $order = $this->create($params);

            $orderRepo = new OrderRepository($order);
            $orderRepo->buildOrderDetails();

            event(new OrderCreateEvent($order));

            return $order;
        } catch (QueryException $e) {
            throw new OrderInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     * @throws OrderInvalidArgumentException
     */
    public function updateOrder(array $params): bool
    {
        try {
            return $this->update($params);
        } catch (QueryException $e) {
            throw new OrderInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function findOrderById(int $id): Order
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new OrderNotFoundException($e);
        }
    }

    /**
     * Return all the orders
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function findProduct(Order $order): Collection
    {
        return $order->product;
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @param array $data
     */
    public function associateProduct(Product $product, int $quantity = 1, array $data = [])
    {
        $this->model->product()->attach($product, [
            'quantity' => $quantity,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'product_description' => $product->description,
            'product_price' => $product->price,
            'product_attribute_id' => isset($data['product_attribute_id']) ? $data['product_attribute_id']: null,
        ]);
        $product->quantity = ($product->quantity - $quantity);
        $product->save();
    }

    /**
     * Send email to customer
     */
    // public function sendEmailToCustomer()
    // {
    //     Mail::to($this->model->customer)
    //         ->send(new SendOrderToCustomerMailable($this->findOrderById($this->model->id)));
    // }

    /**
     * Send email notification to the admin
     */
    public function sendEmailNotificationToAdmin()
    {
        
    }





}