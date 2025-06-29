<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Doğrulama Dil Satırları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki dil satırları doğrulayıcı sınıfı tarafından kullanılan varsayılan
    | hata mesajlarını içerir. Kuralların bazılarının boyut kuralları gibi birden
    | çok sürümü vardır. Bu mesajları burada istediğiniz gibi düzenleyebilirsiniz.
    |
    */

    'required' => ':attribute alanı gereklidir.',
    'max' => [
        'string' => ':attribute alanı :max karakterden fazla olamaz.',
        'numeric' => ':attribute alanı :max değerinden büyük olamaz.',
    ],
    'email' => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'image' => ':attribute alanı bir resim dosyası olmalıdır.',
];
