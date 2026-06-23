<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageTahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $tahunAjaranList = DB::table('tahun_ajaran')
            ->orderByDesc('tahun_ajaran')
            ->get();

        // Statistik per tahun ajaran
        $statistik = [];
        foreach ($tahunAjaranList as $tahun) {
            $ta = $tahun->tahun_ajaran;

            $jumlahMatkul = DB::table('matkul')->where('tahun_ajaran', $ta)->count();
            $jumlahKelas = DB::table('kelas_matkul')->where('tahun_ajaran', $ta)->count();
            $jumlahKelasLama = DB::table('kelas')->where('tahun_ajaran', $ta)->count();

            $jumlahJadwalGanjil = DB::table('jadwal')
                ->where('tahun_ajaran', $ta)
                ->whereRaw("LOWER(semester) LIKE '%ganjil%'")
                ->count();

            $jumlahJadwalGenap = DB::table('jadwal')
                ->where('tahun_ajaran', $ta)
                ->whereRaw("LOWER(semester) LIKE '%genap%'")
                ->count();

            $statistik[] = [
                'tahun_ajaran'      => $ta,
                'id'                => $tahun->id,
                'jumlah_matkul'     => $jumlahMatkul,
                'jumlah_kelas'      => max($jumlahKelas, $jumlahKelasLama),
                'jadwal_ganjil'     => $jumlahJadwalGanjil,
                'jadwal_genap'      => $jumlahJadwalGenap,
                'created_at'        => $tahun->created_at ?? '-',
            ];
        }

        return view('managetahunajaran.index', compact(
            'user_login',
            'countRequest',
            'statistik'
        ));
    }

    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        return view('managetahunajaran.create', compact(
            'user_login',
            'countRequest'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'tahun_ajaran' => 'required|string|max:40',
            ],
            [
                'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
                'tahun_ajaran.max'      => 'Tahun ajaran maksimal 40 karakter.',
            ]
        );

        $tahunAjaran = trim($request->tahun_ajaran);

        // Cek duplikat
        $exists = DB::table('tahun_ajaran')->where('tahun_ajaran', $tahunAjaran)->first();
        if ($exists) {
            return redirect('/managetahunajaran')
                ->with('status', 'Tahun ajaran "' . $tahunAjaran . '" sudah ada!');
        }

        DB::table('tahun_ajaran')->insert([
            'tahun_ajaran' => $tahunAjaran,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Salin data dari tahun ajaran sebelumnya (yang paling baru)
        $tahunSebelumnya = DB::table('tahun_ajaran')
            ->where('tahun_ajaran', '!=', $tahunAjaran)
            ->orderByDesc('tahun_ajaran')
            ->value('tahun_ajaran');

        $jumlahDisalin = 0;

        if ($tahunSebelumnya) {
            // 1. Salin data matkul
            $matkulLama = DB::table('matkul')->where('tahun_ajaran', $tahunSebelumnya)->get();

            foreach ($matkulLama as $m) {
                // Cek apakah matkul sudah ada di tahun baru
                $sudahAda = DB::table('matkul')
                    ->where('kode_matkul', $m->kode_matkul)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->exists();

                if (!$sudahAda) {
                    $dataInsert = [
                        'kode_matkul'          => $m->kode_matkul,
                        'nama_matkul'          => $m->nama_matkul,
                        'sks'                  => $m->sks,
                        'kode_prodi'           => $m->kode_prodi,
                        'kode_semester'        => $m->kode_semester,
                        'perkuliahan_semester'  => $m->perkuliahan_semester,
                        'tahun_ajaran'         => $tahunAjaran,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ];

                    // Salin kolom jenis_matkul jika ada
                    if (isset($m->jenis_matkul)) {
                        $dataInsert['jenis_matkul'] = $m->jenis_matkul;
                    }

                    // Salin kolom tipe_matkul jika ada
                    if (isset($m->tipe_matkul)) {
                        $dataInsert['tipe_matkul'] = $m->tipe_matkul;
                    }

                    DB::table('matkul')->insert($dataInsert);
                    $jumlahDisalin++;
                }
            }

            // 2. Salin data kelas_matkul
            $kelasLama = DB::table('kelas_matkul')->where('tahun_ajaran', $tahunSebelumnya)->get();

            foreach ($kelasLama as $k) {
                $sudahAda = DB::table('kelas_matkul')
                    ->where('kode_matkul', $k->kode_matkul)
                    ->where('nama_kelas', $k->nama_kelas)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->exists();

                if (!$sudahAda) {
                    DB::table('kelas_matkul')->insert([
                        'kode_matkul'      => $k->kode_matkul,
                        'nama_kelas'       => $k->nama_kelas,
                        'kode_rombel'      => $k->kode_rombel,
                        'jumlah_mahasiswa' => $k->jumlah_mahasiswa,
                        'kode_semester'    => $k->kode_semester,
                        'tahun_ajaran'     => $tahunAjaran,
                        'kode_prodi'       => $k->kode_prodi ?? null,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                }
            }

            // 3. Salin data matkul_dosen (dosen pengampu tetap)
            $matkulDosenLama = DB::table('matkul_dosen')->where('tahun_ajaran', $tahunSebelumnya)->get();

            foreach ($matkulDosenLama as $md) {
                $sudahAda = DB::table('matkul_dosen')
                    ->where('kode_matkul', $md->kode_matkul)
                    ->where('kode_dosen', $md->kode_dosen)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->exists();

                if (!$sudahAda) {
                    DB::table('matkul_dosen')->insert([
                        'kode_matkul'  => $md->kode_matkul,
                        'kode_dosen'   => $md->kode_dosen,
                        'tahun_ajaran' => $tahunAjaran,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }
        }

        $pesanSukses = 'Tahun ajaran "' . $tahunAjaran . '" berhasil ditambahkan!';
        if ($jumlahDisalin > 0) {
            $pesanSukses .= ' Data ' . $jumlahDisalin . ' mata kuliah, kelas, dan dosen pengampu berhasil disalin dari tahun ajaran "' . $tahunSebelumnya . '".';
        }

        return redirect('/managetahunajaran')
            ->with('status', $pesanSukses);
    }

    public function destroy(Request $request, $id)
    {
        $tahun = DB::table('tahun_ajaran')->where('id', $id)->first();

        if (!$tahun) {
            return redirect('/managetahunajaran')
                ->with('status', 'Data tahun ajaran tidak ditemukan!');
        }

        $ta = $tahun->tahun_ajaran;

        // Hapus data terkait
        $jadwalIds = DB::table('jadwal')->where('tahun_ajaran', $ta)->pluck('id')->toArray();
        if (count($jadwalIds) > 0) {
            DB::table('jadwal_dosen')->whereIn('jadwal_id', $jadwalIds)->delete();
        }

        DB::table('jadwal')->where('tahun_ajaran', $ta)->delete();
        DB::table('kuliah')->where('tahun_ajaran', $ta)->delete();

        // Hapus kelas_matkul_dosen yang terkait kelas_matkul
        $kelasMatkulIds = DB::table('kelas_matkul')->where('tahun_ajaran', $ta)->pluck('id_kelas')->toArray();
        if (count($kelasMatkulIds) > 0) {
            DB::table('kelas_matkul_dosen')->whereIn('id_kelas', $kelasMatkulIds)->delete();
        }

        DB::table('kelas_matkul')->where('tahun_ajaran', $ta)->delete();
        DB::table('kelas')->where('tahun_ajaran', $ta)->delete();
        DB::table('matkul_dosen')->where('tahun_ajaran', $ta)->delete();
        DB::table('matkul')->where('tahun_ajaran', $ta)->delete();
        DB::table('tahun_ajaran')->where('id', $id)->delete();

        return redirect('/managetahunajaran')
            ->with('status', 'Tahun ajaran "' . $ta . '" beserta seluruh data terkait berhasil dihapus!');
    }
}
