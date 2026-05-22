<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Penghuni;

class Kamar extends Model
{
    protected $fillable = [
        'nomor_kamar',
        'kode_kost',
        'tipe_kamar',
        'harga',
        'status',
        'fasilitas',
        'user_id',
        'penghuni_id',
    ];

    public function kost() {
        // kode_kost di tabel kamars mengacu ke kolom kode_kost pada tabel kosts (bukan id).
        // Karena di controller kost dipetakan dengan kode_kost.
        return $this->belongsTo(Kost::class, 'kode_kost', 'kode_kost');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
}
