<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty($this->user()->email_verified_at);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'restaurant_id' => ['required', 'integer'],
            'payment_type' => ['required', Rule::in(['CASH', 'WALLET', 'MIX'])],
            'description' => ['present', 'max:200'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'total_order' => ['required', 'numeric', 'gte:0'],
            'wallet_payment' => ['nullable', 'numeric', 'gte:0'],
            'order_discount' => ['numeric', 'gte:0'],
            'delivery_fee' => ['numeric', 'gte:0'],
            'delivery_discount' => ['numeric', 'gte:0'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_lat' => ['nullable', 'numeric'],
            'delivery_lng' => ['nullable', 'numeric'],
            'cuisines' => ['present', 'filled'],
            'cuisines.*.cuisine_id' => ['required', 'integer', 'exists:cuisines,id'],
            'cuisines.*.quantity' => ['required', 'integer', 'gte:1'],
            'cuisines.*.description' => ['nullable', 'max:200'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'restaurant_id' => 'Restaurant',
        ];
    }
}
