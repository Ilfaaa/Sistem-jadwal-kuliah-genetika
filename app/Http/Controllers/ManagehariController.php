<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagehariController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $request_keyword = "";
        if($request->keyword){
            $hari = DB::table('hari')->where('nama_hari', 'LIKE', "%{$request->keyword}%")->orWhere('kode_hari', 'LIKE', "%{$request->keyword}%")->get();
            $request_keyword = $request->keyword;
        } else {
            $hari = DB::table('hari')->get();
        }
        return view('managehari.index', compact('hari', 'user_login','request_keyword','countRequest'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $allhari = DB::table('hari')->get();
        $availableDays = ['senin','selasa','rabu','kamis','jum\'at','sabtu','minggu'];
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        

        foreach ($allhari as $hari) {
            array_splice($availableDays, array_search($hari->nama_hari, $availableDays ), 1);       
        }

        return view('managehari.create', compact('user_login','allhari','availableDays','countRequest'));
    }
    public function store(Request $request)
    {   
        $request->validate(
            [
                'nama_hari' => 'required|min:3|max:255|unique:hari',
            ],
            [
                'nama_hari.required' => 'Kolom Nama hari harap di isi.',
                'nama_hari.min' => 'Nama minimal 3 huruf.',
                'nama_hari.max' => 'Nama minimal 255 huruf.',
                'nama_hari.unique' => 'Nama hari Sudah Terdaftar.',
            ]

        );

        $kode_hari = ['senin' => 1,'selasa'=>2,'rabu'=>3,'kamis'=>4,'jum\'at'=>5,'sabtu'=>6,'minggu'=>7];

        $user_login = $request->session()->get('user_login');

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST PRODI
            DB::table('request_waktu')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Hari',
                'kode_waktu' => 0,
                'kode_hari' => $kode_hari[strtolower($request->nama_hari)],
                'nama_hari' => strtolower($request->nama_hari),
                'kode_jam' => '',
                'jam' => '',
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managewaktu/managehari')->with('status', 'Tambah Data Berhasil diajukan ke admin!');
        }

        DB::table('hari')->insert([
            'kode_hari' => $kode_hari[strtolower($request->nama_hari)],
            'nama_hari' => strtolower($request->nama_hari),
        ]);

        return redirect('/managewaktu/managehari')->with('status', 'Data hari Berhasil Ditambahkan!');
    }

    public function destroy(Request $request, $kode_hari)
    {
        $user_login = $request->session()->get('user_login');

        $all_hari = DB::table('hari')->get();

        if(count($all_hari) == 1) {
            return redirect('managewaktu/managehari')->with('status', 'Minimal Tersisa Satu Hari!');
        }

        $hari = DB::table('hari')->where('kode_hari', $kode_hari)->first();

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST HARI
            DB::table('request_waktu')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Hari',
                'kode_waktu' => 0,
                'kode_hari' => $kode_hari,
                'nama_hari' => $hari->nama_hari,
                'kode_jam' => '',
                'jam' => '',
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managewaktu/managehari')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }
        
        DB::table('hari')->where('kode_hari', $kode_hari)->delete();
        DB::table('waktu')->where('kode_hari', $kode_hari)->delete();

        return redirect('/managewaktu/managehari')->with('status', 'Data hari berhasil dihapus!');
    }
}
