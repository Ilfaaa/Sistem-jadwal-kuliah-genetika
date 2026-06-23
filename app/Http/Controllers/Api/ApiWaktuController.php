<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiWaktuController extends Controller
{
    /**
     * GET /api/waktu
     * waktu table removed — return synthetic slots based on hari table.
     */
    public function index(Request $request)
    {
        $hariList = DB::table('hari')->orderBy('kode_hari')->get();
        $waktuList = [];
        $kodeWaktu = 1;

        foreach ($hariList as $hari) {
            $slotMulai = 7 * 60; // 07:00
            $slotIdx = 1;
            while ($slotMulai < 17 * 60 + 15) {
                if ($slotMulai >= 12 * 60 && $slotMulai < 13 * 60) {
                    $slotMulai += 50;
                    continue;
                }
                $jam = str_pad(floor($slotMulai / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($slotMulai % 60, 2, '0', STR_PAD_LEFT);
                $waktuList[] = [
                    'kode_waktu' => $kodeWaktu++,
                    'kode_hari'  => $hari->kode_hari,
                    'nama_hari'  => $hari->nama_hari,
                    'kode_jam'   => $slotIdx,
                    'jam'        => $jam,
                ];
                $slotMulai += 50;
                $slotIdx++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data waktu berhasil diambil.',
            'data'    => $waktuList,
        ]);
    }

    /**
     * GET /api/waktu/{kode_waktu}
     * waktu table removed — returns 404.
     */
    public function show($kode_waktu)
    {
        return response()->json([
            'success' => false,
            'message' => 'Data waktu tidak ditemukan (tabel waktu telah dihapus).',
        ], 404);
    }

    /**
     * GET /api/hari
     * List semua hari.
     */
    public function hari()
    {
        $hari = DB::table('hari')->orderBy('kode_hari')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data hari berhasil diambil.',
            'data'    => $hari,
        ]);
    }

    /**
     * GET /api/jam
     * jam table removed — return synthetic time slots.
     */
    public function jam()
    {
        $slots = [];
        $slotMulai = 7 * 60; // 07:00
        $idx = 1;
        while ($slotMulai < 17 * 60 + 15) {
            if ($slotMulai >= 12 * 60 && $slotMulai < 13 * 60) {
                $slotMulai += 50;
                continue;
            }
            $slots[] = [
                'kode_jam' => $idx,
                'jam'      => str_pad(floor($slotMulai / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($slotMulai % 60, 2, '0', STR_PAD_LEFT),
            ];
            $slotMulai += 50;
            $idx++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Data jam berhasil diambil.',
            'data'    => $slots,
        ]);
    }
}
