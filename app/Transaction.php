<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Get the user of the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include transactions of a given type.
     *
     * @param Builder $query
     * @param string|array $type
     * @return Builder
     */
    public function scopeType(Builder $query, $type)
    {
        if (is_array($type)) {
            return $query->whereIn('transactions.type', $type);
        }

        return $query->where('transactions.type', $type);
    }

    /**
     * Scope a query to only include transactions of a given status.
     *
     * @param Builder $query
     * @param string|array $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('transactions.status', $status);
        }

        return $query->where('transactions.status', $status);
    }

    /**
     * Scope a query to order by latest transaction.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy('transactions.created_at', 'desc');
    }
}
