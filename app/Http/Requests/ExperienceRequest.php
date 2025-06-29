<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'job_title_en' => 'required|string|max:255',
            'job_title_tr' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
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
            'job_title_en' => __('app.job_title_en'),
            'job_title_tr' => __('app.job_title_tr'),
            'company_name' => __('app.company_name'),
            'start_date' => __('app.start_date'),
            'end_date' => __('app.end_date'),
            'description_en' => __('app.description_en'),
            'description_tr' => __('app.description_tr'),
        ];
    }
}
