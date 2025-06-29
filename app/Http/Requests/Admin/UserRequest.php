<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Kullanıcının bu isteği yapma yetkisi olup olmadığını belirle
     *
     * @return bool
     */
    public function authorize()
    {
        // Sadece admin rolüne sahip kullanıcılar yetkili olsun
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * İstek için geçerlilik kurallarını al
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ];
        
        // Kullanıcı oluşturma sırasında (store)
        if ($this->isMethod('post')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        
        // Kullanıcı güncelleme sırasında (update)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'] = [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($this->route('user'))
            ];
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }
        
        return $rules;
    }
    
    /**
     * Doğrulama hata mesajlarını özelleştir
     * 
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => __('validation.name.required'),
            'surname.required' => __('validation.surname.required'),
            'email.required' => __('validation.email.required'),
            'email.email' => __('validation.email.email'),
            'email.unique' => __('validation.email.unique'),
            'password.required' => __('validation.password.required'),
            'password.min' => __('validation.password.min', ['min' => 8]),
            'password.confirmed' => __('validation.password.confirmed'),
            'roles.required' => __('validation.roles.required'),
        ];
    }
}
