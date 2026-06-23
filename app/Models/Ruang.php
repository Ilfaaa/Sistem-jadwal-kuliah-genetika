<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;
    protected $table = "ruang";

    public static function formatName($nama)
    {
        if (empty($nama)) {
            return '-';
        }

        // Convert to lowercase first to normalize
        $nama = strtolower($nama);
        
        // Remove "teknik komputer" (case-insensitive) at the end or anywhere
        $nama = preg_replace('/[,\s]*teknik komputer\s*/i', '', $nama);
        
        // Replace "laboratorium" with "lab" (case-insensitive)
        $nama = preg_replace('/\blaboratorium\b/i', 'lab', $nama);
        
        // Replace "fakultas sains dan matematika" with "fsm" (case-insensitive)
        $nama = preg_replace('/[,\s]*fakultas sains dan matematika/i', ' fsm', $nama);
        
        // Clean up multiple spaces and trim
        $nama = preg_replace('/\s+/', ' ', $nama);
        $nama = trim($nama);
        
        // Title case
        $nama = ucwords($nama);
        
        // Force Fsm to FSM
        $nama = str_replace('Fsm', 'FSM', $nama);
        
        return $nama;
    }
}
