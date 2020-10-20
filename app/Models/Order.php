<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'restaurant_id', 'courier_id', 'user_id', 'order_number', 'payment_type',
        'description', 'coupon_code', 'order_discount', 'delivery_fee', 'delivery_discount',
        'rating', 'status'
    ];

    const STATUS_REJECTED = 'REJECTED';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_PENDING = 'PENDING';
    const STATUS_WAITING_RESTAURANT_CONFIRMATION = 'WAITING RESTAURANT CONFIRMATION';
    const STATUS_FINDING_COURIER = 'FINDING COURIER';
    const STATUS_COURIER_HEADING_RESTAURANT = 'COURIER HEADING RESTAURANT';
    const STATUS_COURIER_WAITING_AT_RESTAURANT = 'COURIER WAITING AT RESTAURANT';
    const STATUS_COURIER_HEADING_CUSTOMER = 'COURIER HEADING CUSTOMER';
    const STATUS_COMPLETED = 'COMPLETED';

    const PAYMENT_TYPE_CASH = 'CASH';
    const PAYMENT_TYPE_WALLET = 'WALLET';
    const PAYMENT_TYPE_MIX = 'MIX';

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
     * Get the order details of the order.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Scope a query to only include orders of a given payment type.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithTotal(Builder $query)
    {
        return $query
            ->select('orders.*')
            ->selectRaw("(
                SELECT SUM(price) AS price FROM order_details
                WHERE order_details.order_id = orders.id
            ) - order_discount + delivery_fee - delivery_discount AS total_price");
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
     * Scope a query to only include nearby of a given lat and lng.
     *
     * @param Builder $query
     * @param $lat
     * @param $lng
     * @param int $radius
     * @return Builder
     */
    public function scopeNearby(Builder $query, $lat, $lng, $radius = 5000)
    {
        return $query->select([
            'orders.id',
            'orders.user_id',
            'orders.restaurant_id',
            'orders.order_number',
            'orders.payment_type',
            'orders.description',
            'orders.delivery_address',
            'orders.delivery_lat',
            'orders.delivery_lng',
            'orders.delivery_fee',
            'orders.created_at',
            DB::raw('SQRT(
                POW(69.1 * (restaurants.lat - ' . addslashes($lat) . '), 2) +
                POW(69.1 * (' . addslashes($lng) . ' - restaurants.lng) * COS(delivery_lat / 57.3), 2)
            ) * 1609.34 AS distance')
        ])
            ->join('restaurants', 'restaurants.id', '=', 'orders.restaurant_id')
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc');
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

    /**
     * Scope a query to order by latest order.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy('orders.created_at', 'desc');
    }

    /**
     * Scope a query to order by newest order.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEarlier(Builder $query)
    {
        return $query->orderBy('orders.created_at', 'desc');
    }

    /**
     * Scope a query to order by today.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query)
    {
        return $query->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
    }

    /**
     * Get order number.
     *
     * @param User $user
     * @return string
     */
    public static function getOrderNumber(User $user)
    {
        $total = $user->orders()->today()->count() + 1;

        return 'ORD-' . sprintf('%s%07s%02s',now()->format('ymd'), $user->id, $total);
    }
}
