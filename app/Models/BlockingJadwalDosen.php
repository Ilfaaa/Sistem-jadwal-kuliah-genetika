<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockingJadwalDosen extends Model
{
    protected $table = 'blocking_jadwal_dosen';

    protected $fillable = [
        'kode_dosen',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];

    // Relasi ke tabel dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }
}