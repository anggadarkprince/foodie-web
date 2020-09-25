<?php

namespace App;

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
}
