<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class OrderController extends Controller
{
    use UpdateStatusOrder;

    /**
     * Store initial order and set status .
     *
     * status after update:
     * Order::STATUS_WAITING_RESTAURANT_CONFIRMATION.
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
                    'status' => Order::STATUS_WAITING_RESTAURANT_CONFIRMATION,
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
     * Confirm requested order by restaurant and ready to finding driver.
     *
     * status after update:
     * Order::STATUS_FINDING_COURIER.
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function confirmOrder(Order $order)
    {
        try {
            $result = $this->updateStatusOrder(
                $order,
                Order::STATUS_FINDING_COURIER,
                Order::STATUS_WAITING_RESTAURANT_CONFIRMATION
            );

            return response()->json(['result' => $result]);
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Order is served by restaurant, courier ready to deliver to customer.
     *
     * status after update:
     * Order::STATUS_COURIER_HEADING_CUSTOMER
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function serveOrder(Order $order)
    {
        try {
            $result = $this->updateStatusOrder(
                $order,
                Order::STATUS_COURIER_HEADING_CUSTOMER,
                Order::STATUS_COURIER_WAITING_AT_RESTAURANT
            );

            return response()->json(['result' => $result]);
        } catch (Throwable $e) {
            return response()->json([
                'errors' => 'Update status order failed, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Rate order after order delivered / completed to customer.
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
