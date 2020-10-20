<?php

namespace App\Models;

use App\Notifications\VerifyEmailCourier;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Courier extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'photo', 'id_card', 'date_of_birth', 'address', 'vehicle_type', 'vehicle_plat'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'datetime:Y-m-d'
    ];

    /**
     * Get the category full path image.
     *
     * @param $value
     * @return string
     */
    public function getPhotoAttribute($value)
    {
        return empty($value) ? url('/img/no-image.png') : Storage::disk('public')->url($value);
    }

    /**
     * Get the orders for the courier.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    /**
     * Scope a query to only include group that match the query.
     *
     * @param Builder $query
     * @param string $q
     * @return Builder
     */
    public function scopeQ(Builder $query, $q = '')
    {
        if (empty($q)) {
            return $query;
        }
        return $query->where(function (Builder $query) use ($q) {
            $query->where('couriers.name', 'LIKE', '%' . $q . '%');
        });
    }

    /**
     * Scope a query to sort group by specific column.
     *
     * @param Builder $query
     * @param $sortBy
     * @param string $sortMethod
     * @return Builder
     */
    public function scopeSort(Builder $query, $sortBy = 'couriers.created_at', $sortMethod = 'desc')
    {
        if (empty($sortBy)) {
            $sortBy = 'couriers.created_at';
        }
        if (empty($sortMethod)) {
            $sortMethod = 'desc';
        }
        return $query->orderBy($sortBy, $sortMethod);
    }

    /**
     * Scope a query to only include group of a greater date creation.
     *
     * @param Builder $query
     * @param $dateFrom
     * @return Builder
     */
    public function scopeDateFrom(Builder $query, $dateFrom)
    {
        if (empty($dateFrom)) return $query;

        try {
            $formattedData = Carbon::parse($dateFrom)->format('Y-m-d');
            return $query->where(DB::raw('DATE(couriers.created_at)'), '>=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }

    /**
     * Scope a query to only include group of a less date creation.
     *
     * @param Builder $query
     * @param $dateTo
     * @return Builder
     */
    public function scopeDateTo(Builder $query, $dateTo)
    {
        if (empty($dateTo)) return $query;

        try {
            $formattedData = Carbon::parse($dateTo)->format('Y-m-d');
            return $query->where(DB::raw('DATE(couriers.created_at)'), '<=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }


    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCourier);
    }
}
