<?php

namespace Database\Seeders;

use App\Models\Record;
use App\Models\User;
use App\Models\Kamar;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user penghuni
        $penghuni1 = User::where('email', 'penghuni1@coba.com')->first();
        $penghuni2 = User::where('email', 'penghuni2@coba.com')->first();
        $penghuni3 = User::where('email', 'penghuni3@coba.com')->first();
        $penghuni4 = User::where('email', 'penghuni4@coba.com')->first();

        // Ambil kamar yang ada (ambil beberapa id pertama)
        $kamars = Kamar::take(6)->pluck('id')->toArray();

        // Fallback kalau kamar kosong
        if (empty($kamars)) {
            $this->command->warn('Tidak ada kamar ditemukan. Seeder records dilewati.');
            return;
        }

        $kamar1 = $kamars[0] ?? null;
        $kamar2 = $kamars[1] ?? $kamar1;
        $kamar3 = $kamars[2] ?? $kamar1;
        $kamar4 = $kamars[3] ?? $kamar1;
        $kamar5 = $kamars[4] ?? $kamar1;
        $kamar6 = $kamars[5] ?? $kamar1;

        $records = [];

        // Penghuni 1 — 3 riwayat kost, mayoritas Baik
        if ($penghuni1) {
            $records = array_merge($records, [
                [
                    'user_id'                  => $penghuni1->id,
                    'kamar_id'                 => $kamar1,
                    'tanggal_masuk'            => '2022-01-01',
                    'tanggal_keluar'           => '2022-12-31',
                    'skor_pembayaran'          => 'Baik',
                    'skor_sikap'               => 'Baik',
                    'skor_perawatan_fasilitas' => 'Baik',
                    'catatan'                  => 'Penghuni sangat kooperatif dan selalu tepat waktu.',
                    'bukti'                    => null,
                    'is_redflag'               => 'no',
                    'status'                   => 'Disetujui',
                ],
                [
                    'user_id'                  => $penghuni1->id,
                    'kamar_id'                 => $kamar2,
                    'tanggal_masuk'            => '2023-01-01',
                    'tanggal_keluar'           => '2023-06-30',
                    'skor_pembayaran'          => 'Baik',
                    'skor_sikap'               => 'Perlu Perhatian',
                    'skor_perawatan_fasilitas' => 'Baik',
                    'catatan'                  => 'Sikap perlu ditingkatkan, pembayaran selalu tepat.',
                    'bukti'                    => null,
                    'is_redflag'               => 'no',
                    'status'                   => 'Disetujui',
                ],
                [
                    'user_id'                  => $penghuni1->id,
                    'kamar_id'                 => $kamar3,
                    'tanggal_masuk'            => '2023-07-01',
                    'tanggal_keluar'           => '2024-01-31',
                    'skor_pembayaran'          => 'Baik',
                    'skor_sikap'               => 'Baik',
                    'skor_perawatan_fasilitas' => 'Perlu Perhatian',
                    'catatan'                  => 'Fasilitas kamar kurang dirawat dengan baik.',
                    'bukti'                    => null,
                    'is_redflag'               => 'no',
                    'status'                   => 'Disetujui',
                ],
            ]);
        }

        // Penghuni 2 — 2 riwayat, ada yang Buruk
        if ($penghuni2) {
            $records = array_merge($records, [
                [
                    'user_id'                  => $penghuni2->id,
                    'kamar_id'                 => $kamar2,
                    'tanggal_masuk'            => '2022-03-01',
                    'tanggal_keluar'           => '2022-09-30',
                    'skor_pembayaran'          => 'Perlu Perhatian',
                    'skor_sikap'               => 'Baik',
                    'skor_perawatan_fasilitas' => 'Perlu Perhatian',
                    'catatan'                  => 'Beberapa kali terlambat membayar.',
                    'bukti'                    => null,
                    'is_redflag'               => 'no',
                    'status'                   => 'Disetujui',
                ],
                [
                    'user_id'                  => $penghuni2->id,
                    'kamar_id'                 => $kamar4,
                    'tanggal_masuk'            => '2023-02-01',
                    'tanggal_keluar'           => '2024-02-28',
                    'skor_pembayaran'          => 'Buruk',
                    'skor_sikap'               => 'Perlu Perhatian',
                    'skor_perawatan_fasilitas' => 'Buruk',
                    'catatan'                  => 'Sering telat bayar dan merusak fasilitas kamar.',
                    'bukti'                    => null,
                    'is_redflag'               => 'no',
                    'status'                   => 'Disetujui',
                ],
            ]);
        }

        // Penghuni 3 — 1 riwayat Menunggu (belum divalidasi superadmin)
        if ($penghuni3) {
            $records = array_merge($records, [
                [
                    'user_id'                  => $penghuni3->id,
                    'kamar_id'                 => $kamar5,
                    'tanggal_masuk'            => '2024-01-01',
                    'tanggal_keluar'           => '2024-06-30',
                    'skor_pembayaran'          => 'Baik',
                    'skor_sikap'               => 'Baik',
                    'skor_perawatan_fasilitas' => 'Baik',
                    'catatan'                  => 'Penghuni teladan, tidak ada masalah.',
                    'bukti'                    => null,
                    'is_redflag'               => 'no',
                    'status'                   => 'Menunggu',
                ],
            ]);
        }

        // Penghuni 4 — belum ada record sama sekali (untuk test empty state)

        foreach ($records as $record) {
            Record::updateOrCreate(
                [
                    'user_id'       => $record['user_id'],
                    'kamar_id'      => $record['kamar_id'],
                    'tanggal_masuk' => $record['tanggal_masuk'],
                ],
                $record
            );
        }

        $this->command->info('RecordSeeder berhasil dijalankan: ' . count($records) . ' record dibuat.');
    }
}