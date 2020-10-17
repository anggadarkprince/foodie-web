<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRestaurant extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $restaurant = $this->route('restaurant');

        return [
            'user_id' => ['bail', 'required', Rule::unique('restaurants')->ignore(optional($restaurant)->id)],
            'name' => ['required', 'max:50'],
            'address' => ['required', 'max:500', 'string'],
            'image' => ['nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
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
            'user_id' => 'Owner',
            'lat' => 'Latitude',
            'lng' => 'Longitude',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'unique' => 'The owner already has a restaurant'
        ];
    }
}
