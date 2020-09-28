<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get user order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->paginate(10);

        return response()->json($orders);
    }

    /**
     * Get user transactions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function transactions(Request $request)
    {
        $transactions = $request->user()->transactions()->paginate(10);

        return response()->json($transactions);
    }
}
