<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiProdiController extends Controller
{
    /**
     * GET /api/prodi
     * List semua program studi. Filter: ?keyword=
     */
    public function index(Request $request)
    {
        $query = DB::table('prodi');

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_prodi', 'LIKE', "%{$keyword}%")
                  ->orWhere('kode_prodi', 'LIKE', "%{$keyword}%");
            });
        }

        $prodi = $query->orderBy('kode_prodi')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data program studi berhasil diambil.',
            'data' => $prodi,
        ]);
    }

    /**
     * GET /api/prodi/{id}
     * Detail satu program studi.
     */
    public function show($id)
    {
        $prodi = DB::table('prodi')->where('id_prodi', $id)->first();

        if (!$prodi) {
            return response()->json([
                'success' => false,
                'message' => 'Data program studi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prodi,
        ]);
    }

    /**
     * POST /api/prodi
     * Tambah program studi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|min:3|max:255|unique:prodi',
            'kode_prodi' => 'required|regex:/^[a-zA-Z]+$/u|max:3|unique:prodi',
        ], [
            'nama_prodi.required' => 'Nama prodi wajib diisi.',
            'nama_prodi.min' => 'Nama prodi minimal 3 karakter.',
            'nama_prodi.unique' => 'Nama prodi sudah terdaftar.',
            'kode_prodi.required' => 'Kode prodi wajib diisi.',
            'kode_prodi.regex' => 'Kode prodi hanya boleh berisi huruf.',
            'kode_prodi.max' => 'Kode prodi maksimal 3 karakter.',
            'kode_prodi.unique' => 'Kode prodi sudah terdaftar.',
        ]);

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Program Studi',
                'kode_manage' => strtoupper($request->kode_prodi),
                'nama_manage' => strtolower($request->nama_prodi),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
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
                'message' => 'Data prodi berhasil dikirimkan ke admin untuk disetujui.',
            ], 201);
        }

        $prodi = new Prodi;
        $prodi->nama_prodi = strtolower($request->nama_prodi);
        $prodi->kode_prodi = strtoupper($request->kode_prodi);
        $prodi->save();

        return response()->json([
            'success' => true,
            'message' => 'Data program studi berhasil ditambahkan.',
            'data' => $prodi,
        ], 201);
    }

    /**
     * PUT /api/prodi/{id}
     * Update program studi.
     */
    public function update(Request $request, $id)
    {
        $prodi = DB::table('prodi')->where('id_prodi', $id)->first();

        if (!$prodi) {
            return response()->json([
                'success' => false,
                'message' => 'Data program studi tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_prodi' => 'required|min:3|max:255',
            'kode_prodi' => 'required|regex:/^[a-zA-Z]+$/u|max:3',
        ], [
            'nama_prodi.required' => 'Nama prodi wajib diisi.',
            'kode_prodi.required' => 'Kode prodi wajib diisi.',
            'kode_prodi.regex' => 'Kode prodi hanya boleh berisi huruf.',
            'kode_prodi.max' => 'Kode prodi maksimal 3 karakter.',
        ]);

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Program Studi',
                'kode_manage' => strtoupper($request->kode_prodi) . '-' . $prodi->kode_prodi,
                'nama_manage' => strtolower($request->nama_prodi),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
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

        $kode_prodi_length = strlen($prodi->kode_prodi);

        try {
            DB::table('prodi')
                ->where('id_prodi', $id)
                ->update([
                    'nama_prodi' => strtolower($request->nama_prodi),
                    'kode_prodi' => strtoupper($request->kode_prodi),
                ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Nama prodi atau kode prodi sudah digunakan.',
            ], 422);
        }

        // Update cascading ke tabel terkait
        $dosen = DB::table('dosen')->where('program_studi', $prodi->nama_prodi)->get();
        foreach ($dosen as $d) {
            DB::table('dosen')
                ->where('kode_dosen', $d->kode_dosen)
                ->update([
                    'kode_dosen' => strtoupper($request->kode_prodi) . substr($d->kode_dosen, $kode_prodi_length),
                    'program_studi' => strtolower($request->nama_prodi),
                ]);
        }

        DB::table('ruang')
            ->where('nama_prodi', $prodi->nama_prodi)
            ->update(['nama_prodi' => strtolower($request->nama_prodi)]);

        $matkul = DB::table('matkul')->where('kode_prodi', $prodi->kode_prodi)->get();
        foreach ($matkul as $m) {
            DB::table('matkul')
                ->where('kode_matkul', $m->kode_matkul)
                ->update([
                    'kode_matkul' => strtoupper($request->kode_prodi) . substr($m->kode_matkul, $kode_prodi_length),
                    'kode_prodi' => strtoupper($request->kode_prodi),
                ]);
        }

        $updated = DB::table('prodi')->where('id_prodi', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data program studi berhasil diubah.',
            'data' => $updated,
        ]);
    }

    /**
     * DELETE /api/prodi/{id}
     * Hapus program studi dan data terkait.
     */
    public function destroy(Request $request, $id)
    {
        $prodi = DB::table('prodi')->where('id_prodi', $id)->first();

        if (!$prodi) {
            return response()->json([
                'success' => false,
                'message' => 'Data program studi tidak ditemukan.',
            ], 404);
        }

        $all_prodi = DB::table('prodi')->count();
        if ($all_prodi <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal tersisa satu program studi.',
            ], 422);
        }

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Program Studi',
                'kode_manage' => $prodi->kode_prodi,
                'nama_manage' => $prodi->nama_prodi,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
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

        $kode_prodi_length = strlen($prodi->kode_prodi);

        DB::table('prodi')->where('id_prodi', $id)->delete();
        DB::table('kuliah')->where('kode_prodi', $prodi->kode_prodi)->delete();
        DB::table('matkul')->where('kode_prodi', $prodi->kode_prodi)->delete();
        DB::table('dosen')->where('program_studi', $prodi->nama_prodi)->delete();

        $kelas = DB::table('kelas')->get();
        foreach ($kelas as $k) {
            if (substr($k->kode_kelas, 0, $kode_prodi_length) == $prodi->kode_prodi) {
                DB::table('kelas')->where('kode_kelas', $k->kode_kelas)->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data program studi dan data terkait berhasil dihapus.',
        ]);
    }
}
