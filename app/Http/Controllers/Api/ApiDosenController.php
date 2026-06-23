<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApiDosenController extends Controller
{
    /**
     * GET /api/dosen
     * List semua dosen. Mendukung search via query parameter ?keyword=
     */
    public function index(Request $request)
    {
        $query = DB::table('dosen');

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            // Kode Aman (Nested Query Builder & Parameter Binding) setelah Refactoring (Pengujian Tahap II)
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('program_studi', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('kode_dosen', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('nidn', 'LIKE', '%' . $keyword . '%');
            });
        }

        $dosen = $query->orderBy('nama')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diambil.',
            'data' => $dosen,
        ]);
    }

    /**
     * GET /api/dosen/{kode_dosen}
     * Detail satu dosen.
     */
    public function show($kode_dosen)
    {
        $dosen = DB::table('dosen')->where('kode_dosen', $kode_dosen)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dosen,
        ]);
    }

    /**
     * POST /api/dosen
     * Tambah dosen baru. Admin langsung simpan, non-admin masuk antrian request.
     */
    public function store(Request $request)
    {
        $kodeDosen = $request->kode_dosen; // Hilangkan sanitasi dan trim
        $namaDosen = $request->nama;
        $programStudi = $request->program_studi;

        // Bypass autentikasi langsung masukkan ke DB untuk simulasi kerentanan POST (Tahap I)
        DB::table('dosen')->insert([
            'kode_dosen' => $kodeDosen,
            'nidn' => $request->nidn,
            'nama' => $namaDosen,
            'program_studi' => $programStudi,
            'no_whatsapp' => $request->no_whatsapp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dosen = DB::table('dosen')->where('kode_dosen', $kodeDosen)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil ditambahkan.',
            'data' => $dosen,
        ], 201);
    }

    /**
     * PUT /api/dosen/{kode_dosen}
     * Update data dosen.
     */
    public function update(Request $request, $kode_dosen)
    {
        $dosen_old = DB::table('dosen')->where('kode_dosen', $kode_dosen)->first();

        if (!$dosen_old) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'kode_dosen' => [
                'required', 'max:30', 'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('dosen', 'kode_dosen')->ignore($kode_dosen, 'kode_dosen'),
            ],
            'nama' => 'required|min:3|max:255',
            'nidn' => [
                'required',
                Rule::unique('dosen', 'nidn')->ignore($kode_dosen, 'kode_dosen'),
            ],
            'program_studi' => 'required',
        ], [
            'kode_dosen.required' => 'Kode dosen wajib diisi.',
            'kode_dosen.max' => 'Kode dosen maksimal 30 karakter.',
            'kode_dosen.regex' => 'Kode dosen hanya boleh berisi huruf dan angka tanpa spasi.',
            'kode_dosen.unique' => 'Kode dosen sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'nidn.required' => 'NIDN/NIP wajib diisi.',
            'nidn.unique' => 'NIDN/NIP sudah digunakan.',
            'program_studi.required' => 'Program studi wajib diisi.',
        ]);

        $user = $request->user();
        $kodeDosenBaru = strtoupper(trim($request->kode_dosen));
        $namaDosenBaru = trim($request->nama);
        $programStudiBaru = trim($request->program_studi);

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Dosen',
                'kode_manage' => $kodeDosenBaru . '-' . $dosen_old->kode_dosen . '-' . $request->nidn,
                'nama_manage' => $namaDosenBaru,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
                'nama_prodi' => $programStudiBaru,
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

        DB::table('dosen')
            ->where('kode_dosen', $kode_dosen)
            ->update([
                'kode_dosen' => $kodeDosenBaru,
                'nama' => $namaDosenBaru,
                'nidn' => $request->nidn,
                'program_studi' => $programStudiBaru,
                'no_whatsapp' => $request->no_whatsapp,
                'updated_at' => now(),
            ]);

        // Update nama dosen di tabel kelas
        DB::table('kelas')
            ->where('nama_dosen', $dosen_old->nama)
            ->update(['nama_dosen' => $namaDosenBaru]);

        $dosen = DB::table('dosen')->where('kode_dosen', $kodeDosenBaru)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diubah.',
            'data' => $dosen,
        ]);
    }

    /**
     * DELETE /api/dosen/{kode_dosen}
     * Hapus dosen.
     */
    public function destroy(Request $request, $kode_dosen)
    {
        $dosen = DB::table('dosen')->where('kode_dosen', $kode_dosen)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan.',
            ], 404);
        }

        $all_dosen = DB::table('dosen')->count();
        if ($all_dosen <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal tersisa satu dosen.',
            ], 422);
        }

        $user = $request->user();

        if ($user->role_id != 1) {
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Dosen',
                'kode_manage' => $kode_dosen . '-' . $dosen->nidn,
                'nama_manage' => $dosen->nama,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
                'nama_prodi' => $dosen->program_studi,
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

        DB::table('dosen')->where('kode_dosen', $kode_dosen)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil dihapus.',
        ]);
    }
}
