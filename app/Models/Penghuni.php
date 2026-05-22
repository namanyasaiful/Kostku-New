<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kamar;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penghuni extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_kamar',
        'status_request',
        'tanggal_masuk',
        'tanggal_keluar',
        'is_redflag',
        'notes_penghuni',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kamar() {
        return $this->belongsTo(Kamar::class, 'nomor_kamar');
    }
}
