<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['group', 'description'];

    /**
     * Get the permission of the group.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permissions');
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
            $query->where('groups.group', 'LIKE', '%' . $q . '%');
            $query->orWhere('groups.description', 'LIKE', '%' . $q . '%');
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
    public function scopeSort(Builder $query, $sortBy = 'groups.created_at', $sortMethod = 'desc')
    {
        if (empty($sortBy)) {
            $sortBy = 'groups.created_at';
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
            return $query->where(DB::raw('DATE(groups.created_at)'), '>=', $formattedData);
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
            return $query->where(DB::raw('DATE(groups.created_at)'), '<=', $formattedData);
        } catch (InvalidFormatException $exception) {
            return $query;
        }
    }
}
