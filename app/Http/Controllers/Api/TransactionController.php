<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionMovement;
use App\Models\Transaction;
use DB;
use Illuminate\Support\Str;
use Throwable;

class TransactionController extends Controller
{

    /**
     * Deposit user balance to make cuisine orders.
     *
     * @param TransactionMovement $request
     */
    public function deposit(TransactionMovement $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $amount = $request->input('amount');

                $user = $request->user();

                // Add amount into balance
                $user->increment('balance', $amount);

                // Add wallet (user) transaction
                $user->transactions()->save([
                    'no_reference' => (string) Str::uuid(),
                    'type' => Transaction::TYPE_TOP_UP,
                    'status' => Transaction::STATUS_IN_PROCESS,
                    'total' => $amount,
                ]);

                return response()->json(['result' => true]);
            });
        }
        catch (Throwable $t) {
            response()->json([
                'result' => false,
                'errors' => 'Top up wallet failed, try again or contact administrator'
            ]);
        }
    }

    /**
     * Withdraw restaurant balance to user's bank account.
     *
     * @param TransactionMovement $request
     */
    public function withdraw(TransactionMovement $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $amount = $request->input('amount');

                $restaurant = $request->user()->restaurant();

                // Subtract amount from balance
                $restaurant->decrement('restaurant_balance', $amount);

                // Withdraw from wallet (restaurant) transaction
                $restaurant->transactions()->save([
                    'no_reference' => (string) Str::uuid(),
                    'type' => Transaction::TYPE_WITHDRAW,
                    'status' => Transaction::STATUS_IN_PROCESS,
                    'total' => $amount * -1,
                ]);

                return response()->json(['result' => true]);
            });
        }
        catch (Throwable $t) {
            response()->json([
                'result' => false,
                'errors' => 'Withdraw restaurant balance failed, try again or contact administrator'
            ]);
        }
    }
}
