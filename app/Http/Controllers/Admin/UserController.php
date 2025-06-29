<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Admin panelinde kullanıcıları listeler
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with(['roles', 'profile'])
            ->orderBy('name')
            ->paginate(15);
            
        return view('admin.users.index', compact('users'));
    }

    /**
     * Yeni bir kullanıcı oluşturma formunu gösterir
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Yeni kullanıcıyı kaydeder
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        
        // Roller atanıyor
        if ($request->has('roles')) {
            foreach ($request->roles as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->addRole($role);
                }
            }
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', __('admin/users.created_successfully'));
    }

    /**
     * Bir kullanıcının detaylarını gösterir
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['roles', 'profile']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Kullanıcı düzenleme formunu gösterir
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Kullanıcı bilgilerini günceller
     *
     * @param  \App\Http\Requests\Admin\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
        ];
        
        // Şifre sadece değiştirilmek istenirse güncelle
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        // Roller güncelleniyor
        if ($request->has('roles')) {
            // Mevcut tüm rolleri sil
            $user->detachRoles($user->roles);
            
            // Yeni rolleri ekle
            foreach ($request->roles as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->addRole($role);
                }
            }
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', __('admin/users.updated_successfully'));
    }

    /**
     * Kullanıcıyı sistemden siler
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Kullanıcı kendini silemez
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', __('admin/users.cannot_delete_self'));
        }
        
        // Kullanıcıya ait tüm verileri ve ilişkileri silme işlemi
        // Bu işlemi Model Events ile yapmak daha doğru olur
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', __('admin/users.deleted_successfully'));
    }
}
