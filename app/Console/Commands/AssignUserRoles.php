<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AssignUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assign-roles {--admin= : E-posta adresi ile admin rolü atanacak kullanıcı}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kullanıcılara roller atar: Varsayılan olarak hepsine "user" rolü, belirtilirse bir kullanıcıya "admin" rolü.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Önce tüm kullanıcıları alıyoruz
        $users = User::all();
        $counter = 0;

        // Tüm kullanıcılara "user" rolünü ata
        foreach ($users as $user) {
            if (!$user->hasRole('user')) {
                $user->addRole('user');
                $counter++;
                $this->info("{$user->name} {$user->surname} kullanıcısına 'user' rolü atandı.");
                Log::info("User role assigned to user ID: {$user->id}, email: {$user->email}");
            } else {
                $this->line("{$user->name} {$user->surname} kullanıcısı zaten 'user' rolüne sahip.");
            }
        }

        $this->info("Toplam {$counter} kullanıcıya 'user' rolü atandı.");

        // Admin rolü atama
        $adminEmail = $this->option('admin');
        if ($adminEmail) {
            $admin = User::where('email', $adminEmail)->first();
            
            if ($admin) {
                if (!$admin->hasRole('admin')) {
                    $admin->addRole('admin');
                    $this->info("{$admin->name} {$admin->surname} kullanıcısına 'admin' rolü atandı.");
                    Log::info("Admin role assigned to user ID: {$admin->id}, email: {$admin->email}");
                } else {
                    $this->line("{$admin->name} {$admin->surname} kullanıcısı zaten 'admin' rolüne sahip.");
                }
            } else {
                $this->error("'{$adminEmail}' e-posta adresine sahip kullanıcı bulunamadı!");
            }
        }

        return Command::SUCCESS;
    }
}
