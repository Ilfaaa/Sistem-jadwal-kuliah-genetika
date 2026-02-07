<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;

class ManageprodiController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $request_keyword = "";
        if($request->keyword){
            $prodi = DB::table('prodi')->where('nama_prodi', 'LIKE', "%{$request->keyword}%")->orWhere('kode_prodi', 'LIKE', "%{$request->keyword}%")->get();
            $request_keyword = $request->keyword;
        } else {
            $prodi = DB::table('prodi')->get();
        }
        return view('manageprodi.index', compact('prodi', 'user_login','request_keyword','countRequest'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        return view('manageprodi.create', compact('user_login','countRequest'));
    }
    public function store(Request $request)
    {   
        $request->validate(
            [
                'nama_prodi' => 'required|min:3|max:255|unique:prodi',
                'kode_prodi' => 'required|regex:/^[a-zA-Z]+$/u|max:3|unique:prodi',
            ],
            [
                'nama_prodi.required' => 'Kolom Nama Prodi harap di isi.',
                'nama_prodi.min' => 'Nama minimal 3 huruf.',
                'nama_prodi.max' => 'Nama minimal 255 huruf.',
                'nama_prodi.unique' => 'Nama Prodi Sudah Terdaftar.',
                'kode_prodi.required' => 'Kolom Kode Prodi harap di isi.',
                'kode_prodi.regex' => 'Kode Prodi hanya berupa huruf.',
                'kode_prodi.max' => 'Kode Prodi maksimal 3 huruf.',
                'kode_prodi.unique' => 'Kode Prodi Sudah Terdaftar.',
            ]

        );

        $user_login = $request->session()->get('user_login');

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST KULIAH
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Program Studi',
                'kode_manage' => strtoupper($request->kode_prodi),
                'nama_manage' => strtolower($request->nama_prodi),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> '',
                'nama_prodi'=> '',
                'nama_matkul'=> '',
                'nama_dosen'=> '',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU DOSEN
            return redirect('/managekuliah/manageprodi')->with('status', 'Data prodi Berhasil dikirimkan ke admin!');

        } else {  

            // INSERT DATA KE TABEL PRODI
            $prodi = new Prodi;
            $prodi->nama_prodi = strtolower($request->nama_prodi);
            $prodi->kode_prodi = strtoupper($request->kode_prodi);
            $prodi->save();

            return redirect('/managekuliah/manageprodi')->with('status', 'Data prodi Berhasil Ditambahkan!');
        }
    }

    public function edit(Request $request, $id_prodi)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $prodi = DB::table('prodi')->where('id_prodi', $id_prodi)->first();

        return view('manageprodi.edit', compact('user_login', 'prodi','countRequest'));
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'nama_prodi' => 'required|min:3|max:255',
                'kode_prodi' => 'required|regex:/^[a-zA-Z]+$/u|max:3',
            ],
            [
                'nama_prodi.required' => 'Kolom Nama Prodi harap di isi.',
                'nama_prodi.min' => 'Nama minimal 3 huruf.',
                'nama_prodi.max' => 'Nama minimal 255 huruf.',
                'kode_prodi.required' => 'Kolom Kode Prodi harap di isi.',
                'kode_prodi.regex' => 'Kode Prodi hanya berupa huruf.',
                'kode_prodi.max' => 'Kode Prodi maksimal 3 huruf.',
            ]

        );
        $user_login = $request->session()->get('user_login');

        $prodi = DB::table('prodi')->where('id_prodi', $id)->first();
        $kode_prodi_length = strlen($prodi->kode_prodi);

        $dosen = DB::table('dosen')->where('program_studi', $prodi->nama_prodi)->get();
        $ruang = DB::table('ruang')->where('nama_prodi', $prodi->nama_prodi)->get();
        $matkul = DB::table('matkul')->where('kode_prodi', $prodi->kode_prodi)->get();
        $kelas = DB::table('kelas')->where('kode_kelas', 'LIKE', "%{$prodi->kode_prodi}%")->get();
        $kuliah = DB::table('kuliah')->where('kode_prodi', $prodi->kode_prodi)->get();


        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL DOSEN
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Program Studi',
                'kode_manage' => strtoupper($request->kode_prodi).'-'.$prodi->kode_prodi,
                'nama_manage' => strtolower($request->nama_prodi),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> '',
                'nama_prodi'=> '',
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/manageprodi')->with('status', 'Perubahan Berhasil diajukan ke admin!');

        } else {   
        
            try {
                DB::table('prodi')
                ->where('id_prodi', $id)
                ->update([
                    'nama_prodi' => strtolower($request->nama_prodi),
                    'kode_prodi' => strtoupper($request->kode_prodi),
                ]);

            } catch(\Illuminate\Database\QueryException $ex){
                return redirect('/managekuliah/manageprodi/'.$id.'/edit')->with('status', 'Gagal! Nama Prodi atau Kode Prodi sudah digunakan.');
            }

            foreach ($dosen as $d) {
                DB::table('dosen')
                ->where('kode_dosen', $d->kode_dosen)
                ->update([
                    'kode_dosen' => strtoupper($request->kode_prodi).substr($d->kode_dosen,$kode_prodi_length),
                    'program_studi' => strtolower($request->nama_prodi),

                ]);
            }

            foreach ($ruang as $r) {
                DB::table('ruang')
                ->where('nama_prodi', $r->nama_prodi)
                ->update([
                    'nama_prodi' => strtolower($request->nama_prodi),

                ]);
            }

            foreach ($matkul as $m) {
                DB::table('matkul')
                ->where('kode_matkul', $m->kode_matkul)
                ->update([
                    'kode_matkul' => strtoupper($request->kode_prodi).substr($m->kode_matkul,$kode_prodi_length),
                    'kode_prodi' => strtoupper($request->kode_prodi),

                ]);
            }

            foreach ($kelas as $k) {
                DB::table('kelas')
                ->where('kode_kelas', $k->kode_kelas)
                ->update([
                    'kode_kelas' => strtoupper($request->kode_prodi).substr($k->kode_kelas,$kode_prodi_length),
                ]);
            }

            foreach ($kuliah as $k) {
                DB::table('kuliah')
                ->where('kode_matkul', $k->kode_matkul)
                ->update([
                    'kode_matkul' => strtoupper($request->kode_prodi).substr($k->kode_matkul,$kode_prodi_length),
                    'kode_dosen' => strtoupper($request->kode_prodi).substr($k->kode_dosen,$kode_prodi_length),
                    'kode_kelas' => strtoupper($request->kode_prodi).substr($k->kode_kelas,$kode_prodi_length),
                    'kode_prodi' => strtoupper($request->kode_prodi)
                ]);
            }

            return redirect('/managekuliah/manageprodi')->with('status', 'Data prodi berhasil diubah');
        }
    }


    public function destroy($id, Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $all_prodi = DB::table('prodi')->get();

        if(count($all_prodi) == 1) {
            return redirect('managekuliah/manageprodi')->with('status', 'Minimal Tersisa Satu Prodi!');
        }

        // Ambil data sebelum di delete
        $prodi = DB::table('prodi')->where('id_prodi', $id)->first();
        $kode_prodi_length = strlen($prodi->kode_prodi);

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST PRODI
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Program Studi',
                'kode_manage' => $prodi->kode_prodi,
                'nama_manage' => $prodi->nama_prodi,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> '',
                'nama_prodi'=> '',
                'nama_matkul'=> '',
                'nama_dosen'=> '',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/manageprodi')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }
        
        DB::table('prodi')->where('id_prodi', $id)->delete();
        DB::table('kuliah')->where('kode_prodi', $prodi->kode_prodi)->delete();
        DB::table('matkul')->where('kode_prodi', $prodi->kode_prodi)->delete();
        DB::table('dosen')->where('program_studi', $prodi->nama_prodi)->delete();

        $kelas = DB::table('kelas')->get();
        foreach ($kelas as $k) {
            if(substr($k->kode_kelas,0,$kode_prodi_length) == $prodi->kode_prodi){
                DB::table('kelas')->where('kode_kelas', $k->kode_kelas)->delete();
            }
        }

        return redirect('/managekuliah/manageprodi')->with('status', 'Data prodi berhasil dihapus!');
    }
}
