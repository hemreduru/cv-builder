<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class LaratrustTestSeeder extends Seeder
{
    /**
     * Test ortamı için Laratrust rol ve izinlerini seed et
     *
     * @return void
     */
    public function run(): void
    {
        // Tablo içeriğini temizle
        app()['db']->table('role_user')->truncate();
        app()['db']->table('permission_role')->truncate();
        app()['db']->table('permission_user')->truncate();

        // Roller oluştur
        $admin = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrator',
            'description' => 'Sistem yöneticisi, tüm yetkiye sahip'
        ]);

        $user = Role::firstOrCreate(['name' => 'user'], [
            'display_name' => 'User',
            'description' => 'Normal kullanıcı'
        ]);

        // İzinler oluştur - her model için CRUD izinleri
        $models = [
            'skill', 'experience', 'education', 'project', 'language', 
            'certificate', 'award', 'reference', 'hobby', 'volunteering', 
            'publication', 'profile', 'social-link'
        ];

        $actions = ['create', 'read', 'update', 'delete'];
        
        // Tüm izinleri tutan array'ler
        $adminPermissionIds = [];
        $userPermissionIds = [];

        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permName = $model . '-' . $action;
                
                $permission = Permission::firstOrCreate(['name' => $permName], [
                    'display_name' => ucfirst($action) . ' ' . ucfirst($model),
                    'description' => ucfirst($action) . ' ' . ucfirst($model) . ' records'
                ]);
                
                // İzin ID'lerini arraylere ekle
                $adminPermissionIds[] = $permission->id;
                $userPermissionIds[] = $permission->id;
            }
        }

        // İzinleri rollere ata
        $admin->permissions()->sync($adminPermissionIds);
        $user->permissions()->sync($userPermissionIds);
    }
}
