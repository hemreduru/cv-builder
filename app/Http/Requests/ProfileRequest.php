<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'title_en' => 'nullable|string|max:255',
            'title_tr' => 'nullable|string|max:255',
            'bio_en' => 'nullable|string|max:1000',
            'bio_tr' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
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
            'title_en' => __('English Title'),
            'title_tr' => __('Turkish Title'),
            'bio_en' => __('English Bio'),
            'bio_tr' => __('Turkish Bio'),
            'phone' => __('Phone'),
            'address' => __('Address'),
            'website' => __('Website'),
            'email' => __('Email'),
        ];
    }
}
