<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicationRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'summary_en' => 'nullable|string|max:1000',
            'summary_tr' => 'nullable|string|max:1000',
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
            'title' => __('app.title'),
            'url' => __('app.url'),
            'summary_en' => __('app.summary_en'),
            'summary_tr' => __('app.summary_tr'),
        ];
    }
}
