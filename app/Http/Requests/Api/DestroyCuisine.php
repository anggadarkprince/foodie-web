<?php

namespace App\Http\Requests\Api;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class DestroyCuisine extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cuisine = $this->route('cuisine');

        $isCustomer = $this->user()->type == User::TYPE_CUSTOMER;
        $isOwnedByRestaurant = $cuisine->restaurant->user->id == $this->user()->id;

        return $isCustomer && $isOwnedByRestaurant;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
