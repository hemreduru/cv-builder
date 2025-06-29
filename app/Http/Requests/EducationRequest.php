<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
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
            'school_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
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
            'school_name' => __('app.school_name'),
            'department' => __('app.department'),
            'start_date' => __('app.start_date'),
            'end_date' => __('app.end_date'),
            'description_en' => __('app.description_en'),
            'description_tr' => __('app.description_tr'),
        ];
    }
}
