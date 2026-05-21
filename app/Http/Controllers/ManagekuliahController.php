<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagekuliahController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest =
            DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        // Ambil list tahun ajaran (string)
        $tahunAjaranList = DB::table('tahun_ajaran')
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Preload tabel referensi (sekali saja untuk menghindari query berulang)
        $prodiByKode    = DB::table('prodi')->get()->keyBy('kode_prodi');
        $semesterByKode = DB::table('semester')->get()->keyBy('kode_semester');

        // Dosen dipakai untuk mapping nama->kode dan kode->nama
        $allDosen = DB::table('dosen')->get();
        $dosenByNama = $allDosen->keyBy('nama');
        $dosenByKode = $allDosen->keyBy('kode_dosen');

        $kuliahByTahun = [];
        $detailKuliahByTahun = [];

        foreach ($tahunAjaranList as $tahunAjaran) {
            $kuliahByTahun[] = [
                'tahun_ajaran' => $tahunAjaran,
                'tabel_kuliah' => [],
            ];

            $detailKuliahByTahun[] = [
                'tahun_ajaran' => $tahunAjaran,
                'tabel_kuliah' => [],
            ];
        }

        // Loop per tahun ajaran
        for ($idxTahun = 0; $idxTahun < count($kuliahByTahun); $idxTahun++) {
            $tahunAjaran = $kuliahByTahun[$idxTahun]['tahun_ajaran'];

            $kelasList = DB::table('kelas')
                ->where('tahun_ajaran', $tahunAjaran)
                ->orderBy('kode_kelas')
                ->get();

            // FIX: collection kosong harus pakai isEmpty()
            if ($kelasList->isEmpty()) {
                $kuliahByTahun[$idxTahun]['tabel_kuliah'] = collect([]);
                $detailKuliahByTahun[$idxTahun]['tabel_kuliah'] = [];
                continue;
            }

            // Matkul per tahun ajaran (dipakai untuk ambil kode_semester dll)
            $matkulThisYear = DB::table('matkul')
                ->where('tahun_ajaran', $tahunAjaran)
                ->get();

            $matkulByNama = $matkulThisYear->keyBy('nama_matkul');
            $matkulByKode = $matkulThisYear->keyBy('kode_matkul');

            // Ambil kuliah existing untuk tahun ajaran ini
            $kuliahExisting = DB::table('kuliah')
                ->where('tahun_ajaran', $tahunAjaran)
                ->orderBy('kode_kuliah')
                ->get();

            // Jika jumlah data tidak sama, kita rebuild (logika lama kamu)
            if ($kelasList->count() !== $kuliahExisting->count()) {
                DB::table('kuliah')->where('tahun_ajaran', $tahunAjaran)->delete();

                $rowsInsert = [];
                $no = 1;

                foreach ($kelasList as $k) {
                    // dari kode_kelas (mengikuti pola lama kamu)
                    $kodeMatkul = substr($k->kode_kelas, 0, -1);
                    $kodeProdi  = substr($k->kode_kelas, 0, -5);

                    // dosen: cari kode dari nama
                    $kodeDosen = isset($dosenByNama[$k->nama_dosen])
                        ? $dosenByNama[$k->nama_dosen]->kode_dosen
                        : '';

                    // semester: cari dari matkul (by nama matkul & tahun ajaran)
                    $kodeSemester = isset($matkulByNama[$k->nama_matkul])
                        ? $matkulByNama[$k->nama_matkul]->kode_semester
                        : '';

                    $rowsInsert[] = [
                        'kode_kuliah'   => $no,
                        'kode_matkul'   => $kodeMatkul,
                        'kode_dosen'    => $kodeDosen,
                        'kode_kelas'    => $k->kode_kelas,
                        'kode_prodi'    => $kodeProdi,
                        'kode_semester' => $kodeSemester,
                        'tahun_ajaran'  => $tahunAjaran,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];

                    $no++;
                }

                if (!empty($rowsInsert)) {
                    DB::table('kuliah')->insert($rowsInsert);
                }

                // refresh
                $kuliahExisting = DB::table('kuliah')
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->orderBy('kode_kuliah')
                    ->get();
            }

            // Simpan kuliah raw
            $kuliahByTahun[$idxTahun]['tabel_kuliah'] = $kuliahExisting;

            // Buat tabel detail untuk view (pakai preload supaya tidak query di dalam loop)
            $kelasByKode = $kelasList->keyBy('kode_kelas');

            $detailRows = [];
            foreach ($kuliahExisting as $k) {
                $kode_kuliah = $k->kode_kuliah;

                $matkul   = isset($matkulByKode[$k->kode_matkul]) ? $matkulByKode[$k->kode_matkul]->nama_matkul : '-';
                $dosen    = isset($dosenByKode[$k->kode_dosen]) ? $dosenByKode[$k->kode_dosen]->nama : '-';
                $kelas    = isset($kelasByKode[$k->kode_kelas]) ? $kelasByKode[$k->kode_kelas]->kelas : '-';
                $prodi    = isset($prodiByKode[$k->kode_prodi]) ? $prodiByKode[$k->kode_prodi]->nama_prodi : '-';
                $semester = isset($semesterByKode[$k->kode_semester]) ? $semesterByKode[$k->kode_semester]->nama_semester : '-';

                // FIX: jangan bungkus compact pakai [ [compact()] ] (jadi nested aneh).
                // Cukup associative array
                $detailRows[] = compact(
                    'kode_kuliah',
                    'matkul',
                    'dosen',
                    'kelas',
                    'prodi',
                    'semester'
                );
            }

            $detailKuliahByTahun[$idxTahun]['tabel_kuliah'] = $detailRows;
        }

        return view('managekuliah.index', compact(
            'kuliahByTahun',
            'detailKuliahByTahun',
            'user_login',
            'countRequest'
        ));
    }

    /**
     * NOTE:
     * Fitur tambah/edit kuliah manual pada controller lama tidak sesuai lagi dengan schema tabel `kuliah`.
     * Sekarang `kuliah` dibentuk dari `kelas`, jadi arahkan user ke manage kelas.
     */
    public function create(Request $request)
    {
        return redirect('/managekuliah/managekelas/create')
            ->with('status', 'Kuliah dibentuk dari data Kelas. Silakan tambah Kelas terlebih dahulu.');
    }

    public function store(Request $request)
    {
        return redirect('/managekuliah/managekelas')
            ->with('status', 'Kuliah dibentuk dari data Kelas. Silakan kelola melalui menu Manage Kelas.');
    }

    public function edit(Request $request, $kode_kuliah)
    {
        return redirect('/managekuliah/managekelas')
            ->with('status', 'Kuliah dibentuk dari data Kelas. Silakan kelola melalui menu Manage Kelas.');
    }

    public function update(Request $request, $kode_kuliah)
    {
        return redirect('/managekuliah/managekelas')
            ->with('status', 'Kuliah dibentuk dari data Kelas. Silakan kelola melalui menu Manage Kelas.');
    }

    public function destroy($kode_kuliah)
    {
        return redirect('/managekuliah/managekelas')
            ->with('status', 'Kuliah dibentuk dari data Kelas. Silakan kelola melalui menu Manage Kelas.');
    }

    // Tetap saya rapikan agar “Laravel-way” + aman
    public function create_action(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/managekuliah/managekelas/create');
        }

        if (!$request->has('prodi')) {
            return response()->json([
                'allDosen' => [],
                'allMatkul' => [],
            ]);
        }

        $prodi = explode("-", $request->get('prodi'));
        $kodeProdi = $prodi[0] ?? null;
        $namaProdi = $prodi[1] ?? null;

        $dosenByProdi  = $namaProdi ? DB::table('dosen')->where('program_studi', $namaProdi)->get() : collect([]);
        $matkulByProdi = $kodeProdi ? DB::table('matkul')->where('kode_prodi', $kodeProdi)->get() : collect([]);

        return response()->json([
            'allDosen'  => $dosenByProdi,
            'allMatkul' => $matkulByProdi,
        ]);
    }
}
