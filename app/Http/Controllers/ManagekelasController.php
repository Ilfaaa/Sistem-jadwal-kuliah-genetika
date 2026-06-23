<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;

class ManagekelasController extends Controller
{
    /**
     * Konsep baru:
     * - Manage Kelas hanya menyimpan data kelas/rombel, mata kuliah, prodi, tahun ajaran, dan kapasitas.
     * - Dosen TIDAK lagi disimpan/ditentukan dari Manage Kelas.
     * - Relasi dosen-matkul-kelas final ditentukan saat Generate Jadwal dan disimpan ke jadwal + jadwal_dosen.
     */

    private function getKodeMatkulFromKodeKelas($kodeKelas)
    {
        return substr($kodeKelas, 0, -1);
    }

    private function getNamaKelasFromKodeKelas($kodeKelas)
    {
        return strtoupper(substr($kodeKelas, -1));
    }

    private function getIdKelasMatkul($kodeKelas, $tahunAjaran)
    {
        $kodeMatkul = $this->getKodeMatkulFromKodeKelas($kodeKelas);
        $namaKelas = $this->getNamaKelasFromKodeKelas($kodeKelas);

        $kelasMatkul = DB::table('kelas_matkul')
            ->where('kode_matkul', $kodeMatkul)
            ->where('nama_kelas', $namaKelas)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        return $kelasMatkul ? $kelasMatkul->id_kelas : null;
    }

    private function labelDosenGenerate()
    {
        return 'Ditentukan saat generate jadwal';
    }

    private function ensureKelasMatkul($kodeKelas, $kelas, $jumlahMahasiswa, $tahunAjaran)
    {
        $kodeMatkul = $this->getKodeMatkulFromKodeKelas($kodeKelas);
        $namaKelas = strtoupper($kelas);

        $matkul = DB::table('matkul')
            ->where('kode_matkul', $kodeMatkul)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        if (!$matkul) {
            return false;
        }

        $existing = DB::table('kelas_matkul')
            ->where('kode_matkul', $kodeMatkul)
            ->where('nama_kelas', $namaKelas)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        if ($existing) {
            DB::table('kelas_matkul')
                ->where('id_kelas', $existing->id_kelas)
                ->update([
                    'kode_rombel'      => $kodeKelas,
                    'jumlah_mahasiswa' => $jumlahMahasiswa,
                    'kode_semester'    => $matkul->kode_semester,
                    'kode_prodi'       => $matkul->kode_prodi ?? null,
                    'updated_at'       => now(),
                ]);

            return true;
        }

        DB::table('kelas_matkul')->insert([
            'kode_matkul'      => $kodeMatkul,
            'nama_kelas'       => $namaKelas,
            'kode_rombel'      => $kodeKelas,
            'jumlah_mahasiswa' => $jumlahMahasiswa,
            'kode_semester'    => $matkul->kode_semester,
            'tahun_ajaran'     => $tahunAjaran,
            'kode_prodi'       => $matkul->kode_prodi ?? null,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return true;
    }

    private function cleanupRelasiDosenLama($kodeKelas, $tahunAjaran)
    {
        // Hanya bersihkan relasi dosen di kelas_matkul_dosen.
        // TIDAK menghapus data kuliah agar data kuliah lama tetap utuh.
        $idKelas = $this->getIdKelasMatkul($kodeKelas, $tahunAjaran);

        if ($idKelas) {
            DB::table('kelas_matkul_dosen')
                ->where('id_kelas', $idKelas)
                ->delete();
        }
    }

    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest =
            DB::table('request_kuliah')->count() +
            DB::table('request_ruang')->count() +
            DB::table('request_waktu')->count();

        $request_keyword = $request->keyword ?? '';
        $tahun_ajaran = DB::table('tahun_ajaran')->get();
        $kelasByTahun = [];

        foreach ($tahun_ajaran as $tahun) {
            $query = DB::table('kelas')->where('tahun_ajaran', $tahun->tahun_ajaran);

            if ($request_keyword !== '') {
                $kw = $request_keyword;

                $query->where(function ($q) use ($kw) {
                    $q->where('kelas.kode_kelas', 'LIKE', "%{$kw}%")
                        ->orWhere('kelas.nama_matkul', 'LIKE', "%{$kw}%")
                        ->orWhere('kelas.kelas', 'LIKE', "%{$kw}%")
                        ->orWhere('kelas.kapasitas_kelas', 'LIKE', "%{$kw}%")
                        ->orWhere('kelas.nama_dosen', 'LIKE', "%{$kw}%");
                });
            }

            $tempKelas = $query
                ->orderBy('kelas.kode_kelas')
                ->get();

            foreach ($tempKelas as $k) {
                $k->nama_dosen = $this->labelDosenGenerate();
            }

            $kelasByTahun[] = [$tahun->tahun_ajaran, $tempKelas];
        }

        $kelas = DB::table('kelas')->get();

        foreach ($kelas as $k) {
            $k->nama_dosen = $this->labelDosenGenerate();
        }

        return view('managekelas.index', compact(
            'kelas',
            'user_login',
            'request_keyword',
            'countRequest',
            'kelasByTahun'
        ));
    }

    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest =
            DB::table('request_kuliah')->count() +
            DB::table('request_ruang')->count() +
            DB::table('request_waktu')->count();

        $tahun_ajaran = DB::table('tahun_ajaran')->get();
        $semester = DB::table('semester')->get();
        $matkul = DB::table('matkul')->get();
        $prodi = DB::table('prodi')->get();

        // Tetap dikirim supaya view lama tidak error. Nanti view create akan kita bersihkan.
        $dosen = collect();

        return view('managekelas.create', compact(
            'user_login',
            'semester',
            'prodi',
            'matkul',
            'dosen',
            'countRequest',
            'tahun_ajaran'
        ));
    }

    public function create_action(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/managekuliah/managekelas/create');
        }

        if ($request->has('prodi')) {
            $prodi = explode('-', $request->get('prodi'), 2);
            $tahun_ajaran = $request->get('tahun_ajaran');

            $matkulByProdiAndTahunAjaran = DB::table('matkul')
                ->where('kode_prodi', $prodi[0])
                ->where('tahun_ajaran', $tahun_ajaran)
                ->get();

            return response()->json([
                'allMatkul' => $matkulByProdiAndTahunAjaran,
            ]);
        }

        if ($request->has('matkul')) {
            $matkul = explode('-', $request->get('matkul'), 2);
            $kodeMatkul = $matkul[0];
            $namaMatkul = strtolower($matkul[1] ?? '');
            $tahun_ajaran = $request->get('tahun_ajaran');

            $kelasLetters = ['A', 'B', 'C', 'D', 'E', 'F'];
            $possibleKodeKelas = array_map(function ($k) use ($kodeMatkul) {
                return $kodeMatkul . $k;
            }, $kelasLetters);

            $kelasByMatkulAndTahunAjaran = DB::table('kelas')
                ->whereIn('kode_kelas', $possibleKodeKelas)
                ->where('nama_matkul', $namaMatkul)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->get();

            return response()->json([
                'kelas' => $kelasByMatkulAndTahunAjaran
            ]);
        }

        return response()->json([]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'tahun_ajaran'    => 'required',
                'prodi'           => 'required|min:3|max:255',
                'matkul'          => 'required|min:3|max:255',
                'kelas'           => 'required',
                'kapasitas_kelas' => 'required|numeric|min:1|max:100',
            ],
            [
                'tahun_ajaran.required'    => 'Harap pilih Tahun Ajaran.',
                'prodi.required'           => 'Harap Pilih Program Studi.',
                'matkul.required'          => 'Harap Pilih Mata Kuliah.',
                'kelas.required'           => 'Harap Pilih Kelas.',
                'kapasitas_kelas.required' => 'Harap Pilih Kapasitas Kelas.',
                'kapasitas_kelas.numeric'  => 'Kapasitas Kelas Harus Berupa Angka.',
                'kapasitas_kelas.min'      => 'Kapasitas Kelas Minimal 1 Orang.',
                'kapasitas_kelas.max'      => 'Kapasitas Kelas maksimal 100 Orang.',
            ]
        );

        $prodi = explode('-', $request->prodi, 2);
        $kodeProdi = $prodi[0];
        $namaProdi = $prodi[1] ?? '';

        $matkul = explode('-', $request->matkul, 2);
        $kodeMatkul = $matkul[0];
        $namaMatkul = $matkul[1] ?? '';
        $kelas = strtoupper($request->kelas);
        $kodeKelas = $kodeMatkul . $kelas;

        $user_login = $request->session()->get('user_login');

        if ((string) $user_login->role_id !== '1') {
            DB::table('request_kuliah')->insert([
                'request'         => 'Tambah Data',
                'manage'          => 'Kelas',
                'kode_manage'     => $kodeKelas,
                'nama_manage'     => $kelas,
                'sks'             => '',
                'kode_prodi'      => $kodeProdi,
                'kode_semester'   => $request->tahun_ajaran,
                'nama_prodi'      => $namaProdi,
                'nama_matkul'     => strtolower($namaMatkul),
                'nama_dosen'      => $this->labelDosenGenerate(),
                'kapasitas_kelas' => $request->kapasitas_kelas,
                'name'            => $user_login->name,
                'image'           => $user_login->image,
                'created_at'      => now(),
            ]);

            return redirect('/managekuliah/managekelas')
                ->with('status', 'Data kelas berhasil dikirimkan ke admin. Dosen akan ditentukan saat generate jadwal.');
        }

        $existingKelas = DB::table('kelas')
            ->where('kode_kelas', $kodeKelas)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        if ($existingKelas) {
            if (strtolower($existingKelas->nama_matkul) === strtolower($namaMatkul)) {
                // True duplicate: same kode_kelas, same nama_matkul, same tahun_ajaran
                return redirect()->back()
                    ->withInput()
                    ->with('kelas_exist', 'Data kelas tersebut sudah ada pada tahun ajaran yang dipilih.');
            }

            // Orphan data: kode_kelas exists but for a different (old) matkul name — update it
            DB::table('kelas')
                ->where('kode_kelas', $kodeKelas)
                ->where('tahun_ajaran', $request->tahun_ajaran)
                ->update([
                    'nama_matkul'     => strtolower($namaMatkul),
                    'nama_dosen'      => $this->labelDosenGenerate(),
                    'kelas'           => $kelas,
                    'kapasitas_kelas' => $request->kapasitas_kelas,
                ]);

            $this->ensureKelasMatkul($kodeKelas, $kelas, $request->kapasitas_kelas, $request->tahun_ajaran);
            $this->cleanupRelasiDosenLama($kodeKelas, $request->tahun_ajaran);

            return redirect('/managekuliah/managekelas')
                ->with('status', 'Data kelas berhasil ditambahkan (data lama diperbarui). Dosen pengajar akan ditentukan otomatis saat generate jadwal.');
        }

        DB::table('kelas')->insert([
            'kode_kelas'      => $kodeKelas,
            'nama_matkul'     => strtolower($namaMatkul),
            'nama_dosen'      => $this->labelDosenGenerate(),
            'kelas'           => $kelas,
            'kapasitas_kelas' => $request->kapasitas_kelas,
            'tahun_ajaran'    => $request->tahun_ajaran,
        ]);

        $this->ensureKelasMatkul($kodeKelas, $kelas, $request->kapasitas_kelas, $request->tahun_ajaran);

        return redirect('/managekuliah/managekelas')
            ->with('status', 'Data kelas berhasil ditambahkan. Dosen pengajar akan ditentukan otomatis saat generate jadwal.');
    }

    public function edit(Request $request, $kode_kelas, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest =
            DB::table('request_kuliah')->count() +
            DB::table('request_ruang')->count() +
            DB::table('request_waktu')->count();

        $tahun_ajaran_db = str_replace('-', '/', $tahun_ajaran);

        $kelas = DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran_db)
            ->first();

        if (!$kelas) {
            return redirect('/managekuliah/managekelas')->with('status', 'Data kelas tidak ditemukan!');
        }

        $kelas->nama_dosen = $this->labelDosenGenerate();

        // Tetap dikirim supaya view edit lama tidak error. Nanti view edit akan kita bersihkan.
        $selectedDosen = [];
        $allDosenByProdi = collect();
        $tahun_ajaran = str_replace('/', '-', $tahun_ajaran_db);

        return view('managekelas.edit', compact(
            'user_login',
            'kelas',
            'allDosenByProdi',
            'selectedDosen',
            'countRequest',
            'tahun_ajaran'
        ));
    }

    public function update(Request $request, $kode_kelas, $tahun_ajaran)
    {
        $request->validate(
            [
                'kapasitas_kelas' => 'required|numeric|min:1|max:100',
            ],
            [
                'kapasitas_kelas.required' => 'Harap Pilih Kapasitas Kelas.',
                'kapasitas_kelas.numeric'  => 'Kapasitas Kelas Harus Berupa Angka.',
                'kapasitas_kelas.min'      => 'Kapasitas Kelas Minimal 1 Orang.',
                'kapasitas_kelas.max'      => 'Kapasitas Kelas maksimal 100 Orang.',
            ]
        );

        $tahun_ajaran_db = str_replace('-', '/', $tahun_ajaran);

        $kelas = Kelas::where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran_db)
            ->first();

        if (!$kelas) {
            return redirect('/managekuliah/managekelas')->with('status', 'Data kelas tidak ditemukan!');
        }

        DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran_db)
            ->update([
                'nama_dosen'      => $this->labelDosenGenerate(),
                'kapasitas_kelas' => $request->kapasitas_kelas,
            ]);

        $this->ensureKelasMatkul($kode_kelas, $kelas->kelas, $request->kapasitas_kelas, $tahun_ajaran_db);
        $this->cleanupRelasiDosenLama($kode_kelas, $tahun_ajaran_db);

        return redirect('/managekuliah/managekelas')
            ->with('status', 'Data kelas berhasil diubah. Dosen pengajar akan ditentukan saat generate jadwal.');
    }

    public function destroy(Request $request, $kode_kelas, $tahun_ajaran)
    {
        $tahun_ajaran_db = str_replace('-', '/', $tahun_ajaran);

        $kelas = Kelas::where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran_db)
            ->first();

        if (!$kelas) {
            return redirect('/managekuliah/managekelas')->with('status', 'Data kelas tidak ditemukan!');
        }

        $idKelas = $this->getIdKelasMatkul($kode_kelas, $tahun_ajaran_db);

        if ($idKelas) {
            DB::table('kelas_matkul_dosen')->where('id_kelas', $idKelas)->delete();
            DB::table('kelas_matkul')->where('id_kelas', $idKelas)->delete();
        }

        DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran_db)
            ->delete();

        DB::table('kuliah')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran_db)
            ->delete();

        return redirect('/managekuliah/managekelas')->with('status', 'Data kelas berhasil dihapus!');
    }
}
