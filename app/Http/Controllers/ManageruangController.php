<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ruang;
use App\Models\Prodi;

class ManageruangController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $request_keyword = "";

        if ($request->keyword) {
            $ruang = DB::table('ruang')
                ->where('nama_ruang', 'LIKE', "%{$request->keyword}%")
                ->orWhere('kode_ruang', 'LIKE', "%{$request->keyword}%")
                ->orWhere('nama_prodi', 'LIKE', "%{$request->keyword}%")
                ->orWhere('kapasitas', 'LIKE', "%{$request->keyword}%")
                ->get();

            $request_keyword = $request->keyword;
        } else {
            $ruang = DB::table('ruang')->get();
        }

        return view('manageruang.index', compact(
            'ruang',
            'user_login',
            'request_keyword',
            'countRequest'
        ));
    }

    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $lastRuang = DB::table('ruang')
            ->orderBy('kode_ruang', 'desc')
            ->first();

        $kodeRuang = $lastRuang ? $lastRuang->kode_ruang + 1 : 1;

        $prodi = Prodi::get();

        return view('manageruang.create', compact(
            'user_login',
            'kodeRuang',
            'countRequest',
            'prodi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_ruang' => 'required|min:2|max:255|unique:ruang,nama_ruang',
                'kapasitas' => 'required|integer|min:1|max:500',
                'nama_prodi' => 'required|min:2|max:255',
                'tipe_ruang' => 'required|in:reguler,laboratorium',
            ],
            [
                'nama_ruang.required' => 'Kolom Nama ruang harap di isi.',
                'nama_ruang.min' => 'Nama ruang minimal 2 huruf.',
                'nama_ruang.max' => 'Nama ruang maksimal 255 huruf.',
                'nama_ruang.unique' => 'Nama ruang sudah terdaftar.',

                'kapasitas.required' => 'Kolom kapasitas ruang harap di isi.',
                'kapasitas.integer' => 'Kapasitas ruang harus berupa angka.',
                'kapasitas.min' => 'Kapasitas ruang minimal 1 mahasiswa.',
                'kapasitas.max' => 'Kapasitas ruang maksimal 500 mahasiswa.',

                'nama_prodi.required' => 'Kolom Nama Prodi harap di isi.',
                'nama_prodi.min' => 'Nama Prodi minimal 2 huruf.',
                'nama_prodi.max' => 'Nama Prodi maksimal 255 huruf.',

                'tipe_ruang.required' => 'Harap pilih tipe ruang.',
                'tipe_ruang.in' => 'Tipe ruang harus Reguler atau Laboratorium.',
            ]
        );

        DB::table('ruang')->insert([
            'kode_ruang' => $request->kode_ruang,
            'nama_ruang' => strtolower($request->nama_ruang),
            'kapasitas' => $request->kapasitas,
            'tipe_ruang' => $request->tipe_ruang,
            'nama_prodi' => strtolower($request->nama_prodi),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/manageruang')->with('status', 'Data ruang berhasil ditambahkan!');
    }

    public function edit(Request $request, $kode_ruang)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $prodi = Prodi::get();

        $ruang = DB::table('ruang')
            ->where('kode_ruang', $kode_ruang)
            ->first();

        if (!$ruang) {
            return redirect('/manageruang')->with('status', 'Data ruang tidak ditemukan.');
        }

        return view('manageruang.edit', compact(
            'user_login',
            'ruang',
            'countRequest',
            'prodi'
        ));
    }

    public function update(Request $request, $kode_ruang)
    {
        $request->validate(
            [
                'nama_ruang' => 'required|min:2|max:255',
                'kapasitas' => 'required|integer|min:1|max:500',
                'nama_prodi' => 'required|min:2|max:255',
                'tipe_ruang' => 'required|in:reguler,laboratorium',
            ],
            [
                'nama_ruang.required' => 'Kolom Nama ruang harap di isi.',
                'nama_ruang.min' => 'Nama ruang minimal 2 huruf.',
                'nama_ruang.max' => 'Nama ruang maksimal 255 huruf.',

                'kapasitas.required' => 'Kolom kapasitas ruang harap di isi.',
                'kapasitas.integer' => 'Kapasitas ruang harus berupa angka.',
                'kapasitas.min' => 'Kapasitas ruang minimal 1 mahasiswa.',
                'kapasitas.max' => 'Kapasitas ruang maksimal 500 mahasiswa.',

                'nama_prodi.required' => 'Kolom Nama Prodi harap di isi.',
                'nama_prodi.min' => 'Nama Prodi minimal 2 huruf.',
                'nama_prodi.max' => 'Nama Prodi maksimal 255 huruf.',

                'tipe_ruang.required' => 'Harap pilih tipe ruang.',
                'tipe_ruang.in' => 'Tipe ruang harus Reguler atau Laboratorium.',
            ]
        );

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
            return redirect('/manageruang/' . $kode_ruang . '/edit')
                ->with('status', 'Gagal! Nama ruang atau kode ruang sudah digunakan.');
        }

        return redirect('/manageruang')->with('status', 'Data ruang berhasil diubah!');
    }

    public function destroy($kode_ruang, Request $request)
    {
        $all_ruang = Ruang::get();

        if (count($all_ruang) == 1) {
            return redirect('/manageruang')->with('status', 'Minimal tersisa satu ruang!');
        }

        $ruang = DB::table('ruang')
            ->where('kode_ruang', $kode_ruang)
            ->first();

        if (!$ruang) {
            return redirect('/manageruang')->with('status', 'Data ruang tidak ditemukan.');
        }

        DB::table('ruang')
            ->where('kode_ruang', $kode_ruang)
            ->delete();

        return redirect('/manageruang')->with('status', 'Data ruang berhasil dihapus!');
    }
}