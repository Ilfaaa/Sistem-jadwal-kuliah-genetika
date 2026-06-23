<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiRuangController extends Controller
{
    /**
     * GET /api/ruang
     * List semua ruang. Filter: ?keyword=, ?nama_prodi=
     */
    public function index(Request $request)
    {
        $query = DB::table('ruang');

        if ($request->has('nama_prodi') && $request->nama_prodi) {
            $query->where('nama_prodi', $request->nama_prodi);
        }

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_ruang', 'LIKE', "%{$keyword}%")
                  ->orWhere('kode_ruang', 'LIKE', "%{$keyword}%")
                  ->orWhere('nama_prodi', 'LIKE', "%{$keyword}%")
                  ->orWhere('kapasitas', 'LIKE', "%{$keyword}%");
            });
        }

        $ruang = $query->orderBy('kode_ruang')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil diambil.',
            'data' => $ruang,
        ]);
    }

    /**
     * GET /api/ruang/{kode_ruang}
     * Detail satu ruang.
     */
    public function show($kode_ruang)
    {
        $ruang = DB::table('ruang')->where('kode_ruang', $kode_ruang)->first();

        if (!$ruang) {
            return response()->json([
                'success' => false,
                'message' => 'Data ruang tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ruang,
        ]);
    }

    /**
     * POST /api/ruang
     * Tambah ruang baru. Admin only.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruang' => 'required|min:2|max:255|unique:ruang,nama_ruang',
            'kapasitas' => 'required|integer|min:1|max:500',
            'nama_prodi' => 'required|min:2|max:255',
            'tipe_ruang' => 'required|in:reguler,laboratorium',
        ], [
            'nama_ruang.required' => 'Nama ruang wajib diisi.',
            'nama_ruang.min' => 'Nama ruang minimal 2 karakter.',
            'nama_ruang.unique' => 'Nama ruang sudah terdaftar.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1.',
            'kapasitas.max' => 'Kapasitas maksimal 500.',
            'nama_prodi.required' => 'Nama prodi wajib diisi.',
            'tipe_ruang.required' => 'Tipe ruang wajib dipilih.',
            'tipe_ruang.in' => 'Tipe ruang harus reguler atau laboratorium.',
        ]);

        // Auto-generate kode_ruang
        $lastRuang = DB::table('ruang')->orderBy('kode_ruang', 'desc')->first();
        $kodeRuang = $lastRuang ? $lastRuang->kode_ruang + 1 : 1;

        DB::table('ruang')->insert([
            'kode_ruang' => $kodeRuang,
            'nama_ruang' => strtolower($request->nama_ruang),
            'kapasitas' => $request->kapasitas,
            'tipe_ruang' => $request->tipe_ruang,
            'nama_prodi' => strtolower($request->nama_prodi),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ruang = DB::table('ruang')->where('kode_ruang', $kodeRuang)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil ditambahkan.',
            'data' => $ruang,
        ], 201);
    }

    /**
     * PUT /api/ruang/{kode_ruang}
     * Update ruang.
     */
    public function update(Request $request, $kode_ruang)
    {
        $ruang = DB::table('ruang')->where('kode_ruang', $kode_ruang)->first();

        if (!$ruang) {
            return response()->json([
                'success' => false,
                'message' => 'Data ruang tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_ruang' => 'required|min:2|max:255',
            'kapasitas' => 'required|integer|min:1|max:500',
            'nama_prodi' => 'required|min:2|max:255',
            'tipe_ruang' => 'required|in:reguler,laboratorium',
        ], [
            'nama_ruang.required' => 'Nama ruang wajib diisi.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'nama_prodi.required' => 'Nama prodi wajib diisi.',
            'tipe_ruang.required' => 'Tipe ruang wajib dipilih.',
            'tipe_ruang.in' => 'Tipe ruang harus reguler atau laboratorium.',
        ]);

        try {
            DB::table('ruang')
                ->where('kode_ruang', $kode_ruang)
                ->update([
                    'nama_ruang' => strtolower($request->nama_ruang),
                    'kapasitas' => $request->kapasitas,
                    'tipe_ruang' => $request->tipe_ruang,
                    'nama_prodi' => strtolower($request->nama_prodi),
                    'updated_at' => now(),
                ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Nama ruang sudah digunakan.',
            ], 422);
        }

        $updated = DB::table('ruang')->where('kode_ruang', $kode_ruang)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil diubah.',
            'data' => $updated,
        ]);
    }

    /**
     * DELETE /api/ruang/{kode_ruang}
     * Hapus ruang.
     */
    public function destroy($kode_ruang)
    {
        $ruang = DB::table('ruang')->where('kode_ruang', $kode_ruang)->first();

        if (!$ruang) {
            return response()->json([
                'success' => false,
                'message' => 'Data ruang tidak ditemukan.',
            ], 404);
        }

        $count = DB::table('ruang')->count();
        if ($count <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal tersisa satu ruang.',
            ], 422);
        }

        DB::table('ruang')->where('kode_ruang', $kode_ruang)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil dihapus.',
        ]);
    }
}
