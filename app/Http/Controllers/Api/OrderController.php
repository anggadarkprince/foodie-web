<?php

namespace App\Http\Controllers\Api;

use App\Courier;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrder;
use App\Order;
use App\OrderDetail;
use App\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

                $walletPayment = $request->input('wallet_payment');
                $totalOrder = $request->input('total_order');
                $request->request->add([
                    'order_number' => Order::getOrderNumber($user),
                    'status' => Order::STATUS_PENDING,
                    'payment_type' => $request->filled('wallet_payment')
                        ? ($walletPayment < $totalOrder ? Order::PAYMENT_TYPE_MIX : Order::PAYMENT_TYPE_WALLET)
                        : Order::PAYMENT_TYPE_CASH
                ]);

                // save order
                $newOrder = new Order($request->except('cuisines'));
                $order = $user->orders()->save($newOrder);

                // save order detail
                $newOrderDetail = collect($request->input('cuisines'))->map(function ($item) {
                    return OrderDetail::mapFromRequest($item);
                });
                $order->cuisines = $order->orderDetails()->saveMany($newOrderDetail);

                // save customer wallet transaction
                if ($request->filled('wallet_payment')) {
                    $user->transactions()->save([
                        'no_reference' => (string) Str::uuid(),
                        'type' => Transaction::TYPE_ORDER,
                        'status' => Transaction::STATUS_IN_PROCESS,
                        'total' => $walletPayment,
                    ]);
                }

                // save restaurant wallet transaction
                $user->transactions()->save([
                    'no_reference' => (string) Str::uuid(),
                    'type' => Transaction::TYPE_ORDER,
                    'status' => Transaction::STATUS_IN_PROCESS,
                    'total' => $newOrderDetail->sum(function ($item) {
                        return $item->price;
                    }),
                ]);

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
    public function updateStatus(Request $request, Order $order)
    {
        try {
            $order->status = $request->input('status');

            $result = $order->save();

            return response()->json(['result' => $result]);
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Rate order and update status.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function rateOrder(Request $request, Order $order)
    {
        try {
            $result = false;
            if ($order->status == Order::STATUS_COMPLETED) {
                $order->rating = $request->star;

                $result = $order->save();
            }
            return response()->json(['result' => $result]);
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }
}
