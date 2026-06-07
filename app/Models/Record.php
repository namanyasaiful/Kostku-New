<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';

    protected $fillable = [
    'user_id',
    'kamar_id',
    'tanggal_masuk',
    'tanggal_keluar',
    'is_redflag',
    'skor_pembayaran',
    'skor_sikap',
    'skor_perawatan_fasilitas',
    'catatan',
    'bukti',
    'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
