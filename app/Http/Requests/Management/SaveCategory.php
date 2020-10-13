<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class SaveCategory extends FormRequest
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
        $category = $this->route('category');

        return [
            'category' => ['bail', 'required', 'max:50'],
            'description' => ['required', 'max:500', 'string'],
            'icon' => [empty(optional($category)->id) ? 'required' : 'nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
        ];
    }
}
