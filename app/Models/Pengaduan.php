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

    public function penghuni()
    {
        return $this->hasOneThrough(
            \App\Models\Penghuni::class,
            \App\Models\User::class,
            'id',
            'user_id',
            'user_id',
            'id'
        );
    }
}
