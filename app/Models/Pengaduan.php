<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'status',
        'balasan',
        'bukti_pengaduan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
