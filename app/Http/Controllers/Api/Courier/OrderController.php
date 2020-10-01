<?php

namespace App\Http\Controllers\Api\Courier;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    /**
     * Take order and update status.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function takeOrder(Request $request, int $id)
    {
        try {
            return DB::transaction(function () use ($id, $request) {
                $order = Order::lockForUpdate()->find($id);

                $taken = false;
                if ($order->status == Order::STATUS_FINDING_COURIER) {
                    $order->status = Order::STATUS_COURIER_HEADING_RESTAURANT;
                    $order->courier_id = $request->user()->id;

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
