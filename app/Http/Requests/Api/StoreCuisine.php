<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCuisine extends FormRequest
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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'cuisine' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
            'description' => ['nullable', 'max:200'],
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/', 'gte:0'],
            'discount' => ['nullable', 'numeric', 'gte:0'],
            'images' => ['nullable'],
            'images.*.image' => ['nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
            'images.*.title' => ['nullable', 'string', 'max:50'],
        ];
    }
}
