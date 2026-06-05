<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'superadmin@coba.com',
            ],
            [
                'nama' => 'Super Admin',
                'telpon' => '081111111111',
                'alamat' => 'Bandung',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );
    }
}