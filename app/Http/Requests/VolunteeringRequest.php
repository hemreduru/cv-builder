<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VolunteeringRequest extends FormRequest
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
            'organization' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'date' => 'required|date',
            'description_en' => 'nullable|string|max:1000',
            'description_tr' => 'nullable|string|max:1000',
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
            'organization' => __('app.organization'),
            'role' => __('app.role'),
            'date' => __('app.date'),
            'description_en' => __('app.description_en'),
            'description_tr' => __('app.description_tr'),
        ];
    }
}
