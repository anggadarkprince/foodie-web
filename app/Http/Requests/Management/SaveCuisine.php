<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class SaveCuisine extends FormRequest
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
        return [
            'restaurant_id' => ['bail', 'required'],
            'category_id' => ['bail', 'required'],
            'cuisine' => ['required', 'max:50'],
            'description' => ['required', 'max:500', 'string'],
            'price' => ['required', 'max:30'],
            'discount' => ['nullable', 'max:30'],
            'image' => ['nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
        ];
    }
}
