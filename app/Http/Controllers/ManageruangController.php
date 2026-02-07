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
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $request_keyword = "";
        if($request->keyword){
            $ruang = DB::table('ruang')->where('nama_ruang', 'LIKE', "%{$request->keyword}%")->orWhere('kode_ruang', 'LIKE', "%{$request->keyword}%")->orWhere('nama_prodi', 'LIKE', "%{$request->keyword}%")->get();
            $request_keyword = $request->keyword;
        } else {
            $ruang = Ruang::get();
        }
        return view('manageruang.index', compact('ruang', 'user_login','request_keyword','countRequest'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $allRuang = Ruang::get();
       if(count($allRuang) > 0) {
            $kodeRuang = $allRuang[count($allRuang) - 1]->kode_ruang + 1;
        } else {
            $kodeRuang = 1; // atau nilai awal default
        }

        $prodi = Prodi::get();


        return view('manageruang.create', compact('user_login','kodeRuang','countRequest','prodi'));
    }
    public function store(Request $request)
    {   
        $request->validate(
            [
                'nama_ruang' => 'required|min:2|max:255|unique:ruang',
                'nama_prodi' => 'required|min:2|max:255',
            ],
            [
                'nama_ruang.required' => 'Kolom Nama ruang harap di isi.',
                'nama_ruang.min' => 'Nama minimal 2 huruf.',
                'nama_ruang.max' => 'Nama minimal 255 huruf.',
                'nama_ruang.unique' => 'Nama ruang Sudah Terdaftar.',
                'nama_prodi.required' => 'Kolom Nama Prodi harap di isi.',
                'nama_prodi.min' => 'Nama Prodi minimal 3 huruf.',
                'nama_prodi.max' => 'Nama Prodi minimal 255 huruf.',
            ]

        );

        $user_login = $request->session()->get('user_login');

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST PRODI
            DB::table('request_ruang')->insert([
                'request' => 'Tambah Data',
                'kode_ruang' => $request->kode_ruang,
                'nama_ruang' => strtolower($request->nama_ruang),
                'nama_prodi' => strtolower($request->nama_prodi),
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/manageruang')->with('status', 'Tambah Data Berhasil diajukan ke admin!');
        }

        DB::table('ruang')->insert([
            'kode_ruang' => $request->kode_ruang,
            'nama_ruang' => strtolower($request->nama_ruang),
            'nama_prodi' => strtolower($request->nama_prodi),
        ]);

        return redirect('/manageruang')->with('status', 'Data ruang Berhasil Ditambahkan!');
    }

    public function edit(Request $request, $kode_ruang)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        $prodi = Prodi::get();
        $ruang = DB::table('ruang')->where('kode_ruang', $kode_ruang)->first();

        return view('manageruang.edit', compact('user_login', 'ruang','countRequest','prodi'));
    }

    public function update(Request $request, $kode_ruang)
    {

        $request->validate(
            [
                'nama_ruang' => 'required|min:2|max:255',
                'nama_prodi' => 'required|min:3|max:255',
            ],
            [
                'nama_ruang.required' => 'Kolom Nama ruang harap di isi.',
                'nama_ruang.min' => 'Nama minimal 2 huruf.',
                'nama_ruang.max' => 'Nama minimal 255 huruf.',
                'nama_prodi.required' => 'Kolom Nama Prodi harap di isi.',
                'nama_prodi.min' => 'Nama Prodi minimal 3 huruf.',
                'nama_prodi.max' => 'Nama Prodi minimal 255 huruf.',
            ]

        );

        $user_login = $request->session()->get('user_login');

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL DOSEN
            DB::table('request_ruang')->insert([
                'request' => 'Ubah Data',
                'kode_ruang' => $kode_ruang,
                'nama_ruang' => strtolower($request->nama_ruang),
                'nama_prodi' => strtolower($request->nama_prodi),
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/manageruang')->with('status', 'Perubahan Berhasil diajukan ke admin!');

        }

        try {
            DB::table('ruang')
            ->where('kode_ruang', $kode_ruang)
            ->update([
                'nama_ruang' => strtolower($request->nama_ruang),
                'nama_prodi' => strtolower($request->nama_prodi),
            ]);

        } catch(\Illuminate\Database\QueryException $ex){
            return redirect('/manageruang/'.$kode_ruang.'/edit')->with('status', 'Gagal! Nama ruang atau Kode ruang sudah digunakan.');
        }

        return redirect('/manageruang')->with('status', 'Data ruang berhasil diubah');
    }


    public function destroy($kode_ruang, Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $all_ruang = Ruang::get();

        if(count($all_ruang) == 1) {
            return redirect('/manageruang')->with('status', 'Minimal Tersisa Satu Ruang!');
        }

        $ruang = DB::table('ruang')->where('kode_ruang', $kode_ruang)->first();


        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST PRODI
            DB::table('request_ruang')->insert([
                'request' => 'Hapus Data',
                'kode_ruang' => $kode_ruang,
                'nama_ruang' => $ruang->nama_ruang,
                'nama_prodi' => $ruang->nama_prodi,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/manageruang')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }

        DB::table('ruang')->where('kode_ruang', $kode_ruang)->delete();


        if($all_ruang[count($all_ruang) - 1]->kode_ruang != count($all_ruang)) {
            for ($i=0; $i < count($all_ruang); $i++) { 
                DB::table('ruang')
                ->where('kode_ruang', $all_ruang[$i]->kode_ruang)
                ->update([
                    'kode_ruang' => $i+1,
                ]);
            }
        }

        return redirect('/manageruang')->with('status', 'Data ruang berhasil dihapus!');
    }
}
