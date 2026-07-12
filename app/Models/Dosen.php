<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = "dosen";
    protected $primaryKey = 'kode_dosen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_dosen',
        'nama',
        'nidn',
        'program_studi',
        'no_whatsapp',
    ];

    public function getNamaProperAttribute()
    {
        return ucwords(strtolower($this->nama));
    }

    public function user()
    {
        return $this->hasOne(User::class, 'kode_dosen', 'kode_dosen');
    }
}