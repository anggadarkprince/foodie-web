<?php


namespace App\Http\Controllers\Api;

use App\Models\Order;

trait UpdateStatusOrder
{
    /**
     * Update status order by precedent condition.
     *
     * @param Order $order
     * @param string $status
     * @param string|null $precedentStatus
     * @return bool
     */
    private function updateStatusOrder(Order $order, string $status, string $precedentStatus = null)
    {
        $allowToUpdate = true;
        if (!is_null($precedentStatus)) {
            $allowToUpdate = $order->status == $precedentStatus;
        }

        if ($allowToUpdate) {
            $order->status = $status;
            return $order->save();
        } else {
            return false;
        }
    }

}
