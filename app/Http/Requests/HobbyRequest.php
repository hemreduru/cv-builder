<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HobbyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_tr' => 'required|string|max:255',
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
            'name_en' => __('app.name_en'),
            'name_tr' => __('app.name_tr'),
        ];
    }
}
