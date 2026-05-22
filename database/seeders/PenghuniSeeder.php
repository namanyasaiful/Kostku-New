<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\Kost;
use App\Models\Penghuni;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenghuniSeeder extends Seeder
{
public function run(): void
{
    $users = [
        [
            'nama' => 'Penghuni 1',
            'username' => 'penghuni1',
            'email' => 'penghuni1@coba.com',
            'telpon' => '081298765431',
            'alamat' => 'Jl. Penghuni No. 1',
            'password' => 'password',
            'role' => 'penghuni',
        ],
        [
            'nama' => 'Penghuni 2',
            'username' => 'penghuni2',
            'email' => 'penghuni2@coba.com',
            'telpon' => '081298765432',
            'alamat' => 'Jl. Penghuni No. 2',
            'password' => 'password',
            'role' => 'penghuni',
        ],
        [
            'nama' => 'Penghuni 3',
            'username' => 'penghuni3',
            'email' => 'penghuni3@coba.com',
            'telpon' => '081298765433',
            'alamat' => 'Jl. Penghuni No. 3',
            'password' => 'password',
            'role' => 'penghuni',
        ],
        [
            'nama' => 'Penghuni 4',
            'username' => 'penghuni4',
            'email' => 'penghuni4@coba.com',
            'telpon' => '081298765434',
            'alamat' => 'Jl. Penghuni No. 4',
            'password' => 'password',
            'role' => 'penghuni',
        ],
    ];

    foreach ($users as $user) {
        User::updateOrCreate(
            ['email' => $user['email']],
            [
                'nama' => $user['nama'],
                'username' => $user['username'],
                'telpon' => $user['telpon'],
                'alamat' => $user['alamat'],
                'password' => Hash::make($user['password']),
                'role' => $user['role'],
            ]
        );
    }
}
}

