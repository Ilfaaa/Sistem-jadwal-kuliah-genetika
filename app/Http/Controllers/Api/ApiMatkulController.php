<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiMatkulController extends Controller
{
    /**
     * GET /api/matkul
     * List mata kuliah. Filter: ?tahun_ajaran=, ?kode_prodi=, ?keyword=
     */
    public function index(Request $request)
    {
        $query = DB::table('matkul');

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->has('kode_prodi') && $request->kode_prodi) {
            $query->where('kode_prodi', $request->kode_prodi);
        }

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_matkul', 'LIKE', "%{$keyword}%")
                  ->orWhere('kode_matkul', 'LIKE', "%{$keyword}%");
            });
        }

        $matkul = $query->orderBy('kode_matkul')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil diambil.',
            'data' => $matkul,
        ]);
    }

    /**
     * GET /api/matkul/{kode_matkul}/{tahun_ajaran}
     * Detail satu mata kuliah.
     */
    public function show($kode_matkul, $tahun_ajaran)
    {
        $matkul = DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        if (!$matkul) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata kuliah tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $matkul,
        ]);
    }

    /**
     * POST /api/matkul
     * Tambah mata kuliah baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required|max:30',
            'nama_matkul' => 'required|min:3|max:255',
            'sks' => 'required|integer|min:1|max:10',
            'kode_prodi' => 'required',
            'tahun_ajaran' => 'required',
        ], [
            'kode_matkul.required' => 'Kode matkul wajib diisi.',
            'nama_matkul.required' => 'Nama matkul wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'kode_prodi.required' => 'Kode prodi wajib diisi.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
        ]);

        // Cek duplikat
        $exists = DB::table('matkul')
            ->where('kode_matkul', $request->kode_matkul)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mata kuliah dengan kode dan tahun ajaran tersebut sudah ada.',
            ], 422);
        }

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Matkul',
                'kode_manage' => $request->kode_matkul,
                'nama_manage' => strtolower($request->nama_matkul),
                'sks' => $request->sks,
                'kode_prodi' => $request->kode_prodi,
                'kode_semester' => $request->kode_semester ?? '',
                'nama_prodi' => '',
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user->name,
                'image' => $user->image,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data matkul berhasil dikirimkan ke admin untuk disetujui.',
            ], 201);
        }

        DB::table('matkul')->insert([
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => strtolower($request->nama_matkul),
            'sks' => $request->sks,
            'jenis_matkul' => $request->jenis_matkul ?? 'teori',
            'tipe_matkul' => $request->tipe_matkul ?? 'wajib',
            'kode_prodi' => $request->kode_prodi,
            'kode_semester' => $request->kode_semester ?? '',
            'tahun_ajaran' => $request->tahun_ajaran,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $matkul = DB::table('matkul')
            ->where('kode_matkul', $request->kode_matkul)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil ditambahkan.',
            'data' => $matkul,
        ], 201);
    }

    /**
     * PUT /api/matkul/{kode_matkul}/{tahun_ajaran}
     * Update mata kuliah.
     */
    public function update(Request $request, $kode_matkul, $tahun_ajaran)
    {
        $matkul = DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        if (!$matkul) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata kuliah tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_matkul' => 'required|min:3|max:255',
            'sks' => 'required|integer|min:1|max:10',
        ], [
            'nama_matkul.required' => 'Nama matkul wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
        ]);

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Matkul',
                'kode_manage' => $kode_matkul,
                'nama_manage' => strtolower($request->nama_matkul),
                'sks' => $request->sks,
                'kode_prodi' => $matkul->kode_prodi ?? '',
                'kode_semester' => $request->kode_semester ?? $matkul->kode_semester ?? '',
                'nama_prodi' => '',
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user->name,
                'image' => $user->image,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perubahan berhasil diajukan ke admin.',
            ]);
        }

        DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'nama_matkul' => strtolower($request->nama_matkul),
                'sks' => $request->sks,
                'jenis_matkul' => $request->jenis_matkul ?? $matkul->jenis_matkul,
                'tipe_matkul' => $request->tipe_matkul ?? $matkul->tipe_matkul,
                'kode_semester' => $request->kode_semester ?? $matkul->kode_semester,
                'updated_at' => now(),
            ]);

        $updated = DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil diubah.',
            'data' => $updated,
        ]);
    }

    /**
     * DELETE /api/matkul/{kode_matkul}/{tahun_ajaran}
     * Hapus mata kuliah.
     */
    public function destroy(Request $request, $kode_matkul, $tahun_ajaran)
    {
        $matkul = DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->first();

        if (!$matkul) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata kuliah tidak ditemukan.',
            ], 404);
        }

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Matkul',
                'kode_manage' => $kode_matkul,
                'nama_manage' => $matkul->nama_matkul,
                'sks' => $matkul->sks ?? '',
                'kode_prodi' => $matkul->kode_prodi ?? '',
                'kode_semester' => $matkul->kode_semester ?? '',
                'nama_prodi' => '',
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user->name,
                'image' => $user->image,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan hapus berhasil diajukan ke admin.',
            ]);
        }

        DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil dihapus.',
        ]);
    }
}
