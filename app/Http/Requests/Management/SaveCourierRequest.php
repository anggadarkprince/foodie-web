<?php

namespace App\Http\Requests\Management;

use Illuminate\Foundation\Http\FormRequest;

class SaveCourierRequest extends FormRequest
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
        $courier = $this->route('courier');

        return [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'max:50', 'email', 'unique:couriers,email,' . optional($courier)->id . ',id'],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'photo' => [empty($courier) ? 'required' : 'nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
            'id_card' => ['required', 'min:10', 'max:20'],
            'date_of_birth' => ['required', 'date', 'min:3', 'max:20'],
            'vehicle_type' => ['required', 'min:3', 'max:30'],
            'vehicle_plat' => ['required', 'min:3', 'max:12'],
            'address' => ['required', 'min:5', 'max:100'],
        ];
    }
}
