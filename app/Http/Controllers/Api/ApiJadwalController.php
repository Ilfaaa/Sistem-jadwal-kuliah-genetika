<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiJadwalController extends Controller
{
    /**
     * GET /api/jadwal
     * List jadwal. Filter: ?semester=, ?tahun_ajaran=, ?keyword=
     */
    public function index(Request $request)
    {
        $query = DB::table('jadwal');

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->has('keyword') && $request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('matkul', 'LIKE', "%{$keyword}%")
                  ->orWhere('dosen', 'LIKE', "%{$keyword}%")
                  ->orWhere('ruang', 'LIKE', "%{$keyword}%")
                  ->orWhere('hari', 'LIKE', "%{$keyword}%")
                  ->orWhere('kelas', 'LIKE', "%{$keyword}%");
            });
        }

        $jadwal = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal berhasil diambil.',
            'data' => $jadwal,
            'meta' => [
                'total' => $jadwal->count(),
            ],
        ]);
    }

    /**
     * GET /api/jadwal/{id}
     * Detail satu jadwal.
     */
    public function show($id)
    {
        $jadwal = DB::table('jadwal')->where('id', $id)->first();

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data jadwal tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ]);
    }

    /**
     * GET /api/kuliah
     * List kuliah. Filter: ?tahun_ajaran=
     */
    public function kuliah(Request $request)
    {
        $query = DB::table('kuliah');

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $kuliah = $query->orderBy('kode_kuliah')->get();

        // Enrich dengan data dari tabel referensi
        $dosenByKode = DB::table('dosen')->get()->keyBy('kode_dosen');
        $matkulByKode = DB::table('matkul')->get()->keyBy('kode_matkul');
        $prodiByKode = DB::table('prodi')->get()->keyBy('kode_prodi');
        $kelasByKode = DB::table('kelas')->get()->keyBy('kode_kelas');

        $enriched = $kuliah->map(function ($k) use ($dosenByKode, $matkulByKode, $prodiByKode, $kelasByKode) {
            return [
                'kode_kuliah' => $k->kode_kuliah,
                'kode_matkul' => $k->kode_matkul,
                'kode_dosen' => $k->kode_dosen,
                'kode_kelas' => $k->kode_kelas,
                'kode_prodi' => $k->kode_prodi,
                'kode_semester' => $k->kode_semester,
                'tahun_ajaran' => $k->tahun_ajaran,
                'nama_matkul' => isset($matkulByKode[$k->kode_matkul]) ? $matkulByKode[$k->kode_matkul]->nama_matkul : '-',
                'nama_dosen' => isset($dosenByKode[$k->kode_dosen]) ? $dosenByKode[$k->kode_dosen]->nama : '-',
                'nama_prodi' => isset($prodiByKode[$k->kode_prodi]) ? $prodiByKode[$k->kode_prodi]->nama_prodi : '-',
                'kelas' => isset($kelasByKode[$k->kode_kelas]) ? $kelasByKode[$k->kode_kelas]->kelas : '-',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data kuliah berhasil diambil.',
            'data' => $enriched->values(),
            'meta' => [
                'total' => $enriched->count(),
            ],
        ]);
    }

    /**
     * GET /api/semester
     * List semua semester.
     */
    public function semester()
    {
        $semester = DB::table('semester')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data semester berhasil diambil.',
            'data' => $semester,
        ]);
    }
}
