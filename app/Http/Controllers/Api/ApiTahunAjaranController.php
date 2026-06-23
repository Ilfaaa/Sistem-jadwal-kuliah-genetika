<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiTahunAjaranController extends Controller
{
    /**
     * GET /api/tahun-ajaran
     * List semua tahun ajaran.
     */
    public function index()
    {
        $tahunAjaran = DB::table('tahun_ajaran')
            ->orderBy('tahun_ajaran', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data tahun ajaran berhasil diambil.',
            'data' => $tahunAjaran,
        ]);
    }

    /**
     * POST /api/tahun-ajaran
     * Tambah tahun ajaran baru. Admin only.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|unique:tahun_ajaran,tahun_ajaran',
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.unique' => 'Tahun ajaran sudah terdaftar.',
        ]);

        DB::table('tahun_ajaran')->insert([
            'tahun_ajaran' => $request->tahun_ajaran,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tahunAjaran = DB::table('tahun_ajaran')
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil ditambahkan.',
            'data' => $tahunAjaran,
        ], 201);
    }

    /**
     * DELETE /api/tahun-ajaran/{id}
     * Hapus tahun ajaran. Admin only.
     */
    public function destroy($id)
    {
        $tahunAjaran = DB::table('tahun_ajaran')->where('id', $id)->first();

        if (!$tahunAjaran) {
            return response()->json([
                'success' => false,
                'message' => 'Tahun ajaran tidak ditemukan.',
            ], 404);
        }

        DB::table('tahun_ajaran')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil dihapus.',
        ]);
    }
}
