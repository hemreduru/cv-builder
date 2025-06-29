<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description_en' => 'nullable|string|max:1000',
            'description_tr' => 'nullable|string|max:1000',
            'is_featured' => 'boolean',
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
            'image_file' => __('app.image'),
            'description_en' => __('app.description_en'),
            'description_tr' => __('app.description_tr'),
            'is_featured' => __('app.is_featured'),
        ];
    }
}
