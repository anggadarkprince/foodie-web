<?php

namespace App\Http\Controllers\Api;

use App\Courier;
use App\Cuisine;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrder;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    /**
     * Store initial order.
     *
     * @param StoreOrder $request
     * @return JsonResponse
     */
    public function store(StoreOrder $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $user = $request->user();

                $request->request->add([
                    'order_number' => Order::getOrderNumber($user),
                    'status' => Order::STATUS_PENDING,
                ]);

                $newOrder = new Order($request->except('cuisines'));

                $order = $user->orders()->save($newOrder);

                $newOrderDetail = collect($request->input('cuisines'))->map(function ($item) {
                    return OrderDetail::mapFromRequest($item);
                });

                $order->cuisines = $order->orderDetails()->saveMany($newOrderDetail);

                return response()->json($order);
            });
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Update status order.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function status(Request $request, Order $order)
    {
        try {
            $order->status = $request->input('status');

            $order->save();

            return response()->json(['result' => true]);
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Take order and update status.
     *
     * @param int $id
     * @param Courier $courier
     * @return JsonResponse
     */
    public function takeOrder(int $id, Courier $courier)
    {
        try {
            return DB::transaction(function () use ($id, $courier) {
                $order = Order::lockForUpdate()->find($id);

                $taken = false;
                if ($order->status == Order::STATUS_FINDING_COURIER) {
                    $order->status = Order::STATUS_COURIER_HEADING_RESTAURANT;
                    $order->courier_id = $courier->id;

                    $taken = $order->save();
                }
                return response()->json(['result' => $taken]);
            });
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }
}
