<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = '@admin#2026';

        Admin::updateOrCreate(
            ['email' => 'admin@parsu.edu.ph'],
            [
                'name' => 'Administrator',
                'email' => 'admin@parsu.edu.ph',
                'password' => Hash::make($password),
            ]
        );

        // Remove old credential entry if it exists
        Admin::where('email', 'admin@psu.edu.ph')->delete();

        $this->command->info('Admin account updated.');
        $this->command->info('  Email:    admin@parsu.edu.ph');
        $this->command->info('  Password: ' . $password);
    }
}
