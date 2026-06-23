<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiKelasController extends Controller
{
    /**
     * GET /api/kelas
     * List kelas. Filter: ?tahun_ajaran=, ?keyword=
     */
    public function index(Request $request)
    {
        $query = DB::table('kelas');

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_kelas', 'LIKE', "%{$keyword}%")
                  ->orWhere('kelas', 'LIKE', "%{$keyword}%")
                  ->orWhere('nama_matkul', 'LIKE', "%{$keyword}%")
                  ->orWhere('nama_dosen', 'LIKE', "%{$keyword}%");
            });
        }

        $kelas = $query->orderBy('kode_kelas')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil.',
            'data' => $kelas,
        ]);
    }

    /**
     * GET /api/kelas/{kode_kelas}/{tahun_ajaran}
     * Detail satu kelas.
     */
    public function show($kode_kelas, $tahun_ajaran)
    {
        $kelas = DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kelas,
        ]);
    }

    /**
     * POST /api/kelas
     * Tambah kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|max:30',
            'kelas' => 'required',
            'nama_matkul' => 'required',
            'nama_dosen' => 'required',
            'kapasitas_kelas' => 'required|integer|min:1',
            'tahun_ajaran' => 'required',
        ], [
            'kode_kelas.required' => 'Kode kelas wajib diisi.',
            'kelas.required' => 'Nama kelas wajib diisi.',
            'nama_matkul.required' => 'Nama matkul wajib diisi.',
            'nama_dosen.required' => 'Nama dosen wajib diisi.',
            'kapasitas_kelas.required' => 'Kapasitas kelas wajib diisi.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
        ]);

        // Cek duplikat
        $exists = DB::table('kelas')
            ->where('kode_kelas', $request->kode_kelas)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas dengan kode dan tahun ajaran tersebut sudah ada.',
            ], 422);
        }

        DB::table('kelas')->insert([
            'kode_kelas' => $request->kode_kelas,
            'kelas' => $request->kelas,
            'nama_matkul' => strtolower($request->nama_matkul),
            'nama_dosen' => $request->nama_dosen,
            'sks' => $request->sks ?? 0,
            'kapasitas_kelas' => $request->kapasitas_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kelas = DB::table('kelas')
            ->where('kode_kelas', $request->kode_kelas)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil ditambahkan.',
            'data' => $kelas,
        ], 201);
    }

    /**
     * PUT /api/kelas/{kode_kelas}/{tahun_ajaran}
     * Update kelas.
     */
    public function update(Request $request, $kode_kelas, $tahun_ajaran)
    {
        $kelas = DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'kelas' => 'required',
            'nama_matkul' => 'required',
            'nama_dosen' => 'required',
            'kapasitas_kelas' => 'required|integer|min:1',
        ]);

        DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'kelas' => $request->kelas,
                'nama_matkul' => strtolower($request->nama_matkul),
                'nama_dosen' => $request->nama_dosen,
                'sks' => $request->sks ?? $kelas->sks,
                'kapasitas_kelas' => $request->kapasitas_kelas,
                'updated_at' => now(),
            ]);

        $updated = DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diubah.',
            'data' => $updated,
        ]);
    }

    /**
     * DELETE /api/kelas/{kode_kelas}/{tahun_ajaran}
     * Hapus kelas.
     */
    public function destroy($kode_kelas, $tahun_ajaran)
    {
        $kelas = DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan.',
            ], 404);
        }

        DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil dihapus.',
        ]);
    }
}
