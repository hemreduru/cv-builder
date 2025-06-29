<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->username)) {
                $user->username = self::generateUsername($user->name, $user->surname);
            }
        });
        
        static::updating(function ($user) {
            if ($user->isDirty('name') || $user->isDirty('surname')) {
                $user->username = self::generateUsername($user->name, $user->surname);
            }
        });
    }
    
    /**
     * Generate a username from name and surname.
     *
     * @param string $name
     * @param string $surname
     * @return string
     */
    public static function generateUsername($name, $surname)
    {
        // İsmin ilk harfini ve soyadını al
        $firstChar = mb_substr($name, 0, 1);
        
        // Türkçe karakterleri ingilizce karakterlere çevir ve küçük harfe dönüştür
        $username = Str::slug($firstChar . $surname, '');
        
        // Eğer bu username zaten varsa sonuna rakam ekle
        $count = 2;
        $originalUsername = $username;
        
        while (self::where('username', $username)->exists()) {
            $username = $originalUsername . $count;
            $count++;
        }
        
        return $username;
    }
}
