<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Get the user of the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courier of the order.
     */
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * Get the restaurant of the order.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Scope a query to only include orders of a given payment type.
     *
     * @param Builder $query
     * @param string|array $type
     * @return Builder
     */
    public function scopePaymentType(Builder $query, $type)
    {
        if (is_array($type)) {
            return $query->whereIn('orders.payment_type', $type);
        }

        return $query->where('orders.payment_type', $type);
    }

    /**
     * Scope a query to only include orders of a given status.
     *
     * @param Builder $query
     * @param string|array $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('orders.status', $status);
        }

        return $query->where('orders.status', $status);
    }

    /**
     * Scope a query to only include active orders.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereNotIn('orders.status', ['COMPLETED', 'REJECTED', 'CANCELED']);
    }
}
