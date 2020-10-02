<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name' => ['bail', 'required', 'max:50', 'min:3'],
            'email' => ['required', 'max:50', 'email', 'unique:users,email,' . $userId . ',id'],
            'current_password' => ['required', 'password:api'],
            'password' => ['sometimes', 'nullable', 'min:6', 'confirmed'],
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
            'name' => 'Your name',
            'email' => 'Email address',
        ];
    }
}
