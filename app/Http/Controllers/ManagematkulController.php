<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Matkul;

class ManagematkulController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        // list tahun ajaran yang ada
        $tahun_ajaran = DB::table('tahun_ajaran')->get();

        // Preload dosen pengampu
        $allMatkulDosen = DB::table('matkul_dosen')
            ->join('dosen', 'dosen.kode_dosen', '=', 'matkul_dosen.kode_dosen')
            ->select('matkul_dosen.kode_matkul', 'matkul_dosen.tahun_ajaran', 'dosen.kode_dosen', 'dosen.nama')
            ->get();

        $dosenPengampuMap = [];
        foreach ($allMatkulDosen as $md) {
            $key = $md->kode_matkul . '|' . $md->tahun_ajaran;
            if (!isset($dosenPengampuMap[$key])) {
                $dosenPengampuMap[$key] = [];
            }
            $dosenPengampuMap[$key][] = $md->kode_dosen;
        }

        $matkulByTahun = [];
        foreach ($tahun_ajaran as $tahun) {
            $matkulByTahun[] = [$tahun->tahun_ajaran];
        }

        for ($i = 0; $i < count($matkulByTahun); $i++) {
            // FIX: sebelumnya $matkulByTahun[$i] itu array, harusnya ambil index 0
            $tahunVal = $matkulByTahun[$i][0];

            $tempMatkul = DB::table('matkul')->where('tahun_ajaran', $tahunVal)->get();
            $matkulByTahun[$i][] = $tempMatkul ? $tempMatkul : [];
        }

        // Preload data semester untuk filter ganjil/genap
        $allSemester = DB::table('semester')->get();
        $semesterByKode = [];
        foreach ($allSemester as $s) {
            $semesterByKode[$s->kode_semester] = $s;
        }

        return view('managematkul.index', compact('user_login','countRequest','matkulByTahun','dosenPengampuMap','semesterByKode'));
    }

    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $semester = DB::table('semester')->get();
        $prodi = DB::table('prodi')->get();
        $allDosen = DB::table('dosen')->orderBy('nama')->get();

        return view('managematkul.create', compact('user_login','semester','prodi','countRequest','allDosen'));
    }

    public function store(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        // VALIDASI FORM (basic)
        $request->validate(
            [
                'nama_matkul' => 'required|min:3|max:255',
                'jumlah_sks' => 'required|numeric',
                'program_studi' => 'required',
                'semester' => 'required',
                'perkuliahan_semester' => 'required|integer|min:1|max:8',
                'tahun_ajaran' => 'required',
                'jenis_matkul' => 'required|in:teori,praktikum',
                'tipe_matkul' => 'required|in:wajib,pilihan',
            ],
            [
                'nama_matkul.required' => 'Kolom nama matkul harap di isi.',
                'nama_matkul.min' => 'Nama matkul minimal 3 huruf.',
                'nama_matkul.max' => 'Nama matkul minimal 255 huruf.',
                'jumlah_sks.required' => 'Harap piih jumlah sks.',
                'jumlah_sks.numeric' => 'Jumlah sks harus berupa angka.',
                'program_studi.required' => 'Harap pilih salah satu program studi.',
                'semester.required' => 'Harap pilih salah satu semester.',
                'perkuliahan_semester.required' => 'Harap pilih salah satu perkuliahan semester.',
                'perkuliahan_semester.integer' => 'Perkuliahan semester harus berupa angka.',
                'perkuliahan_semester.min' => 'Perkuliahan semester minimal 1.',
                'perkuliahan_semester.max' => 'Perkuliahan semester maksimal 8.',
                'tahun_ajaran.required' => 'Harap pilih salah satu tahun ajaran.',
                'jenis_matkul.required' => 'Harap pilih jenis mata kuliah.',
                'jenis_matkul.in' => 'Jenis mata kuliah harus Teori atau Praktikum.',
                'tipe_matkul.required' => 'Harap pilih tipe mata kuliah.',
                'tipe_matkul.in' => 'Tipe mata kuliah harus Wajib atau Pilihan.',
            ]
        );
        // END VALIDASI

// VALIDASI TAMBAHAN: ganjil/genap harus sesuai semester
$semesterRow = DB::table('semester')->where('kode_semester', $request->semester)->first();
if (!$semesterRow) {
    return back()->withErrors(['semester' => 'Semester tidak valid.'])->withInput();
}

$namaSemester = strtolower($semesterRow->nama_semester ?? '');
$perkuliahan = (int) $request->perkuliahan_semester;

$isGenap = str_contains($namaSemester, 'genap');
$isGanjil = str_contains($namaSemester, 'ganjil');

if ($isGenap && ($perkuliahan % 2 !== 0)) {
    return back()->withErrors([
        'perkuliahan_semester' => 'Semester Genap hanya boleh memilih Perkuliahan Semester Genap (2,4,6,8).'
    ])->withInput();
}

if ($isGanjil && ($perkuliahan % 2 !== 1)) {
    return back()->withErrors([
        'perkuliahan_semester' => 'Semester Ganjil hanya boleh memilih Perkuliahan Semester Ganjil (1,3,5,7).'
    ])->withInput();
}

        // AMBIL TABEL PRODI DENGAN PRODI SESUAI REQUEST
        $prodi = DB::table('prodi')->where('kode_prodi', $request->program_studi)->first();
        if (!$prodi) {
            return back()->withErrors(['program_studi' => 'Program studi tidak valid.'])->withInput();
        }

        // AMBIL TABEL MATKUL BERDASARKAN KODE PRODI dan Tahun Ajaran
        $matkul = DB::table('matkul')
            ->where('kode_prodi', $prodi->kode_prodi)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->orderBy('kode_matkul', 'asc')
            ->get();

        // VALIDASI MATKUL MAKSIMAL 9999
        if (count($matkul) >= 9999) {
            return redirect('/managekuliah/managematkul')->with('status', 'Mata Kuliah Maksimal 9999!');
        }

        // masukkan tahun ajaran ke tabel tahun_ajaran
        $tahun_ajaran_exist = DB::table('tahun_ajaran')->where('tahun_ajaran', $request->tahun_ajaran)->first();
        if (!$tahun_ajaran_exist) {
            DB::table('tahun_ajaran')->insert([
                'tahun_ajaran' => $request->tahun_ajaran
            ]);
        }

        /* GENERATE KODE MATKUL */
        $request_kode_matkul = "";

        // JIKA MATKUL MASIH KOSONG
        if (count($matkul) == 0) {
            $request_kode_matkul = $request->program_studi . "0001";
        }

        // ambil panjang kode prodi (2 / 3)
        $kode_prodi_length = strlen($prodi->kode_prodi);

        // looping keseluruhan matkul berdasarkan request prodi
        for ($i = 0; $i < count($matkul); $i++) {
            // jika kode matkul tidak sama dengan iterasi looping + 1 (yang berarti ada kode yang masih bisa di pakai)
            if (substr($matkul[$i]->kode_matkul, $kode_prodi_length) != ($i + 1)) {

                // generate angka kode matkul
                $last_numb = 0;
                $index_len = strlen($i + 1);

                if ($index_len == 1) $last_numb = "000" . ($i + 1);
                elseif ($index_len == 2) $last_numb = "00" . ($i + 1);
                elseif ($index_len == 3) $last_numb = "0" . ($i + 1);
                else $last_numb = $i + 1;

                $request_kode_matkul = substr($matkul[$i]->kode_matkul, 0, $kode_prodi_length) . $last_numb;
                break;
            }
        }

        // jika kode matkul masih belum didapatkan (tambah 1 dari kode terakhir)
        if (!$request_kode_matkul && count($matkul) > 0) {
            $request_kode_matkul = ++$matkul[count($matkul) - 1]->kode_matkul;
        }

        if ($user_login->role_id != '1') {
            // INSERT DATA KE TABEL REQUEST
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Mata Kuliah',
                'kode_manage' => $request_kode_matkul,
                'nama_manage' => strtolower($request->nama_matkul),
                'sks' => $request->jumlah_sks,
                'kode_prodi' => $request->program_studi,
                'kode_semester' => $request->semester . ':' . $request->perkuliahan_semester . ':' . $request->tahun_ajaran . ':' . $request->jenis_matkul . ':' . $request->tipe_matkul,
                'nama_prodi' => '',
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            return redirect('/managekuliah/managematkul')->with('status', 'Data matkul Berhasil dikirimkan ke admin!');
        } else {
            // INSERT DATA KE TABEL MATKUL
            $matkulModel = new Matkul;
            $matkulModel->kode_matkul = $request_kode_matkul;
            $matkulModel->nama_matkul = strtolower($request->nama_matkul);
            $matkulModel->sks = $request->jumlah_sks;
            $matkulModel->jenis_matkul = $request->jenis_matkul;
            $matkulModel->tipe_matkul = $request->tipe_matkul;
            $matkulModel->kode_prodi = $request->program_studi;
            $matkulModel->kode_semester = $request->semester;
            $matkulModel->perkuliahan_semester = $request->perkuliahan_semester;
            $matkulModel->tahun_ajaran = $request->tahun_ajaran;
            $matkulModel->save();

            // Simpan dosen pengampu
            $dosenPengampu = $request->dosen_pengampu ?? [];
            foreach ($dosenPengampu as $kodeDosen) {
                DB::table('matkul_dosen')->insert([
                    'kode_matkul'  => $request_kode_matkul,
                    'kode_dosen'   => $kodeDosen,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            return redirect('/managekuliah/managematkul')->with('status', 'Data matkul Berhasil Ditambahkan!');
        }
    }

    public function edit(Request $request, $kode_matkul, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $tahun_ajaran_temp = explode('-', $tahun_ajaran);
        $tahun_ajaran = implode('/', $tahun_ajaran_temp);

        $matkul = DB::table('matkul')->where('kode_matkul', $kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->first();
        $semester = DB::table('semester')->get();
        $allDosen = DB::table('dosen')->orderBy('nama')->get();

        // Ambil dosen pengampu yang sudah terpasang
        $selectedDosen = DB::table('matkul_dosen')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->pluck('kode_dosen')
            ->toArray();

        $tahun_ajaran_temp = explode('/', $tahun_ajaran);
        $tahun_ajaran = implode('-', $tahun_ajaran_temp);

        return view('managematkul.edit', compact('user_login', 'matkul', 'semester', 'countRequest', 'tahun_ajaran', 'allDosen', 'selectedDosen'));
    }

    public function update(Request $request, $kode_matkul, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');

        // VALIDASI FORM
        $request->validate(
            [
                'nama_matkul' => 'required|min:3|max:255',
                'jumlah_sks' => 'required|numeric',
                'periode_semester' => 'required',
                'perkuliahan_semester' => 'required',
                'jenis_matkul' => 'required|in:teori,praktikum',
                'tipe_matkul' => 'required|in:wajib,pilihan',
            ],
            [
                'nama_matkul.required' => 'Kolom nama matkul harap di isi.',
                'nama_matkul.min' => 'Nama matkul minimal 3 huruf.',
                'nama_matkul.max' => 'Nama matkul minimal 255 huruf.',
                'jumlah_sks.required' => 'Harap piih jumlah sks.',
                'jumlah_sks.numeric' => 'Jumlah sks harus berupa angka.',
                'periode_semester.required' => 'Harap pilih Periode Semester.',
                'perkuliahan_semester.required' => 'Harap pilih Semester Perkuliahan.',
                'jenis_matkul.required' => 'Harap pilih jenis mata kuliah.',
                'jenis_matkul.in' => 'Jenis mata kuliah harus Teori atau Praktikum.',
                'tipe_matkul.required' => 'Harap pilih tipe mata kuliah.',
                'tipe_matkul.in' => 'Tipe mata kuliah harus Wajib atau Pilihan.',
            ]
        );
        //END VALIDASI

        $tahun_ajaran_temp = explode('-', $tahun_ajaran);
        $tahun_ajaran = implode('/', $tahun_ajaran_temp);

        // AMBIL DATA MATKUL SEBELUM DI EDIT
        $matkul_old = DB::table('matkul')->where('kode_matkul', $kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->first();

        if ($user_login->role_id != '1') {
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Mata Kuliah',
                'kode_manage' => $kode_matkul,
                'nama_manage' => strtolower($request->nama_matkul),
                'sks' => $request->jumlah_sks,
                'kode_prodi' => $matkul_old->kode_prodi,
                'kode_semester' => $request->periode_semester . ':' . $request->perkuliahan_semester . ':' . $tahun_ajaran . ':' . $request->jenis_matkul . ':' . $request->tipe_matkul,
                'nama_prodi' => '',
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            return redirect('/managekuliah/managematkul')->with('status', 'Perubahan Berhasil diajukan ke admin!');
        } else {
            DB::table('matkul')
                ->where('kode_matkul', $kode_matkul)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->update([
                    'nama_matkul' => strtolower($request->nama_matkul),
                    'sks' => $request->jumlah_sks,
                    'jenis_matkul' => $request->jenis_matkul,
                    'tipe_matkul' => $request->tipe_matkul,
                    'kode_semester' => $request->periode_semester,
                    'perkuliahan_semester' => $request->perkuliahan_semester,
                ]);

            DB::table('kelas')
                ->where('nama_matkul', $matkul_old->nama_matkul)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->update([
                    'nama_matkul' => strtolower($request->nama_matkul),
                ]);

            // Update dosen pengampu: hapus lama, masukkan baru
            DB::table('matkul_dosen')
                ->where('kode_matkul', $kode_matkul)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->delete();

            $dosenPengampu = $request->dosen_pengampu ?? [];
            foreach ($dosenPengampu as $kodeDosen) {
                DB::table('matkul_dosen')->insert([
                    'kode_matkul'  => $kode_matkul,
                    'kode_dosen'   => $kodeDosen,
                    'tahun_ajaran' => $tahun_ajaran,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            return redirect('/managekuliah/managematkul')->with('status', 'Data matkul berhasil diubah');
        }
    }

    public function destroy(Request $request, $kode_matkul, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');

        $tahun_ajaran_temp = explode('-', $tahun_ajaran);
        $tahun_ajaran = implode('/', $tahun_ajaran_temp);

        $all_matkul = DB::table('matkul')->where('tahun_ajaran', $tahun_ajaran)->get();

        if (count($all_matkul) == 1) {
            return redirect('managekuliah/managematkul')->with('status', 'Minimal Tersisa Satu Matkul!');
        }

        $matkul = DB::table('matkul')->where('kode_matkul', $kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->first();

        if ($user_login->role_id != '1') {
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Mata Kuliah',
                'kode_manage' => $kode_matkul,
                'nama_manage' => strtolower($matkul->nama_matkul),
                'sks' => $matkul->sks,
                'kode_prodi' => $matkul->kode_prodi,
                'kode_semester' => $matkul->kode_semester . ':' . $matkul->perkuliahan_semester . ':' . $tahun_ajaran,
                'nama_prodi' => '',
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            return redirect('/managekuliah/managematkul')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }

        // Hapus relasi dosen pengampu
        DB::table('matkul_dosen')->where('kode_matkul', $kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();

        DB::table('matkul')->where('kode_matkul', $kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();
        DB::table('kelas')->where('nama_matkul', $matkul->nama_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();
        DB::table('kuliah')->where('kode_matkul', $matkul->kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();

        return redirect('/managekuliah/managematkul')->with('status', 'Data matkul berhasil dihapus!');
    }
}