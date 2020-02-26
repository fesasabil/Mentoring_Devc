<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Transformers\Orders\OrderTransformer;
// use App\Repositories\Orders\OrderRepository;
// use App\Repositories\Orders\OrderRepositoryInterface;
// use App\Models\Category;
// use ProductRepository;

class OrderController extends Controller
{
    public function createOrder(Request $request, Order $order)
    {
        $this->validate($request, [
            'invoice'   => 'required',
        ]);

        $order = $order->create([
            'invoice'   => $request->invoice,
            'discounts' => $request->discounts,
        ]);

        $response = fractal()
            ->item($order)
            ->transformWith(new OrderTransformer)
            ->toArray();

        return response()->json($response, 201);
    }

    // public function showOrders(Order $order)
    // {
        
    // }
    // /**
    //  * @var OrderRepositoryInterface
    //  */
    // private $orderRepo;

    // public function __construct(OrderRepositoryInterface $orderRepository)
    // {
    //     $this->orderRepo = $orderRepository;
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     $list = $this->orderRepo->listOrders('created_at', 'desc');

    //     if (request()->has('q')) {
    //         $list = $this->orderRepo->searchOrder(request()->input('q') ?? '');
    //     }

    //     // $orders = $this->orderRepo->paginateArrayResults();
    // }

    //  /**
    //  * Display the specified resource.
    //  *
    //  * @param  int $orderId
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($orderId)
    // {
    //     $order = $this->orderRepo->findOrderById($orderId);

    //     $orderRepo = new OrderRepository($order);
    //     $order = $orderRepo->getProduct()->first();
    //     $item = $orderRepo->listOrders();

    //     return view('orders.show', [
    //         'order' => $order,
    //         'item' => $item
    //     ]);
    // }

    // public function edit($orderId)
    // {
    //     $order = $this->orderRepo->findOrderById($orderId);

    //     $orderRepo = new OrderRepository($order);
    //     $order = $orderRepo->getProduct()->first();
    //     $item = $orderRepo->listOrders();

    //     return view('orders.edit', [
    //         'order' => $order,
    //         'item' => $item
    //     ]);
    // }

    // public function update(Request $request, $orderId)
    // {
    //     $order = $this->orderRepo->findOrderById($orderId);
    //     $orderRepo = new OrderRepository($order);

    //     if ($request->has('total_paid') && $request->input('total_paid') != null ) {
    //         $orderData = $request->except('_method', '_token');
    //     } else {
    //         $orderData = $request->except('_method', '_token', 'total_paid');
    //     }

    //     $orderRepo->updateOrder($orderData);

    //     return redirect()->route('orders.update', $orderId);
    // }

    // public function delete()
    // {
        
    // }
}
