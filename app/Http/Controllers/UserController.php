<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tüm kullanıcıları listeler
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('profile')
            ->orderBy('name')
            ->paginate(10);
            
        return view('users.index', compact('users'));
    }

    /**
     * Belirli bir kullanıcının verilerini görüntüler
     *
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Kullanıcının profili ve diğer verilerini yükle
        $user->load([
            'profile',
            'experiences',
            'education',
            'projects',
            'skills',
            'languages',
            'certificates',
            'awards',
            'references',
            'hobbies',
            'volunteerings',
            'publications',
            'socialLinks'
        ]);
        
        return view('users.show', [
            'user' => $user,
            'pageTitle' => __('users.view_profile_title', ['name' => $user->name])
        ]);
    }
}
