<?php

namespace Database\Seeders;

use App\Models\Kost;
use App\Models\User;
use App\Models\Kamar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengelolaSeeder extends Seeder
{
    public function run(): void
    {
        $pengelolas = [
            [
                'nama' => 'Pengelola 1',
                'username' => 'pengelola1',
                'email' => 'pengelola1@coba.com',
                'telpon' => '081234567891',
                'alamat' => 'Jl. Bandung No. 1',
                'nama_kost' => 'Kost Mawar',
                'alamat_kost' => 'Jl. Mawar No. 1',
            ],
            [
                'nama' => 'Pengelola 2',
                'username' => 'pengelola2',
                'email' => 'pengelola2@coba.com',
                'telpon' => '081234567892',
                'alamat' => 'Jl. Bandung No. 2',
                'nama_kost' => 'Kost Melati',
                'alamat_kost' => 'Jl. Melati No. 2',
            ],
            [
                'nama' => 'Pengelola 3',
                'username' => 'pengelola3',
                'email' => 'pengelola3@coba.com',
                'telpon' => '081234567893',
                'alamat' => 'Jl. Bandung No. 3',
                'nama_kost' => 'Kost Anggrek',
                'alamat_kost' => 'Jl. Anggrek No. 3',
            ],
        ];

        foreach ($pengelolas as $index => $data) {

            // 1. Buat user
            $pengelola = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nama' => $data['nama'],
                    'username' => $data['username'],
                    'telpon' => $data['telpon'],
                    'alamat' => $data['alamat'],
                    'password' => Hash::make('password'),
                    'role' => 'pengelola',
                ]
            );

            // 2. Buat kost (ambil id yang pasti)
            $kost = Kost::updateOrCreate(
                ['user_id' => $pengelola->id], // cari berdasarkan user
                [
                    'kode_kost' => 'KST-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'nama_kost' => $data['nama_kost'],
                    'alamat_kost' => $data['alamat_kost'],
                    'sertifikat' => null,
                ]
            );

            // 3. Hapus kamar lama biar tidak duplicate
            // Hapus berdasarkan kepemilikan pengelola (user_id) agar aman.
            Kamar::where('user_id', $pengelola->id)->delete();

            // 4. Insert kamar dengan jumlah sesuai request (2 kamar per pengelola)
            $kamars = [
                [
                    'kode_kost' => $kost->id,
                    'nomor_kamar' => 'A01',
                    'tipe_kamar' => 'standard',
                    'status' => 'kosong',
                    'harga' => 500000,
                    'fasilitas' => 'Kamar standard',
                    'user_id' => $pengelola->id,
                ],
                [
                    'kode_kost' => $kost->id,
                    'nomor_kamar' => 'A02',
                    'tipe_kamar' => 'standard',
                    'status' => 'terisi',
                    'harga' => 550000,
                    'fasilitas' => 'Kamar standard luas',
                    'user_id' => $pengelola->id,
                ],
            ];

            Kamar::insert($kamars);
        }
    }
}


