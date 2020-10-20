<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    const TYPE_CUSTOMER = 'CUSTOMER';
    const TYPE_MANAGEMENT = 'MANAGEMENT';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the avatar image.
     *
     * @param mixed $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        return empty($value) ? url('/img/no-image.png') : Storage::disk('public')->url($value);
    }

    /**
     * Get the group of the user.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Get the restaurant for the user.
     */
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    /**
     * Get the permissions of the user.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePermissions(Builder $query)
    {
        return $query
            ->select([
                'permissions.permission',
                'permissions.description',
            ])
            ->distinct()
            ->join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('groups', 'groups.id', '=', 'user_groups.group_id')
            ->join('group_permissions', 'group_permissions.group_id', '=', 'groups.id')
            ->join('permissions', 'permissions.id', '=', 'group_permissions.permission_id');
    }

    /**
     * Check the permissions is owned by user.
     *
     * @param Builder $query
     * @param $permission
     * @return int
     */
    public function scopeHasPermission(Builder $query, $permission) {
        $hasPermission = $this->scopePermissions($query)->where('permission', $permission)->get();

        return $hasPermission->count() > 0;
    }

    /**
     * Scope a query to only include customer user.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCustomer(Builder $query)
    {
        return $query->where('users.type', self::TYPE_CUSTOMER);
    }

    /**
     * Scope a query to only include user that match the query.
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
            $query->where('users.name', 'LIKE', '%' . $q . '%');
            $query->orWhere('users.email', 'LIKE', '%' . $q . '%');
            $query->orWhere('users.type', 'LIKE', '%' . $q . '%');
            $query->orWhereHas('groups', function (Builder $query) use ($q) {
                $query->where('group', 'LIKE', '%' . $q . '%');
            });
        });
    }

    /**
     * Scope a query to sort user by specific column.
     *
     * @param Builder $query
     * @param $sortBy
     * @param string $sortMethod
     * @return Builder
     */
    public function scopeSort(Builder $query, $sortBy = 'users.created_at', $sortMethod = 'desc')
    {
        if (empty($sortBy)) {
            $sortBy = 'users.created_at';
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
            return $query->where(DB::raw('DATE(users.created_at)'), '>=', $formattedData);
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
            return $query->where(DB::raw('DATE(users.created_at)'), '<=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }
}
