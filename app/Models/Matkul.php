<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $table = 'matkul';

    // Tambahkan atribut fillable agar bisa mass assignment
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'sks',
        'jenis_matkul', // 'praktikum' atau 'teori'
        'tipe_matkul',  // 'wajib' atau 'pilihan'
        'tahun_ajaran',
        'kode_prodi'
    ];

    // Helper untuk mengecek tipe matkul
    public function isPraktikum(): bool
    {
        return strtolower($this->jenis_matkul ?? '') === 'praktikum';
    }
}