<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'avatar' => ['nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
            'groups' => ['present', 'filled', 'array'],
        ];
    }
}
