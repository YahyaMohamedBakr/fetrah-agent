<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@fetrah.com'],
            [
                'name' => 'مدير الموقع',
                'email' => 'admin@fetrah.com',
                'password' => bcrypt('admin123'),
            ]
        );

        $this->command->info('Admin user created: admin@fetrah.com / admin123');
    }
}
