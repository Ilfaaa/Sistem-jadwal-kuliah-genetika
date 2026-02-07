<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;
use App\Models\Prodi;

class ManagedosenController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        $request_keyword = "";
        if($request->keyword){
            $dosen = Dosen::where('nama', 'LIKE', "%{$request->keyword}%")->orWhere('program_studi', 'LIKE', "%{$request->keyword}%")->orWhere('kode_dosen', 'LIKE', "%{$request->keyword}%")->orWhere('nidn', 'LIKE', "%{$request->keyword}%")->get();
            $request_keyword = $request->keyword;
        } else {
            $dosen = Dosen::get();
        }
        return view('managedosen.index', compact('dosen', 'user_login','request_keyword','countRequest'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        $prodi = Prodi::get();

        return view('managedosen.create', compact('user_login','prodi','countRequest'));
    }
    public function store(Request $request)
    {
        // VALIDASI FORM TAMBAH DOSEN   
        $request->validate(
            [
                'nama' => 'required|min:3|max:255',
                'nidn' => 'required',
                'program_studi' => 'required'
            ],
            [
                'nama.required' => 'Kolom nama harap di isi.',
                'nama.min' => 'Nama minimal 3 huruf.',
                'nama.max' => 'Nama minimal 255 huruf.',
                'nidn.required' => 'NIDN Atau NIP Tidak Boleh Kosong.',
                'program_studi.required' => 'Harap pilih salah satu program studi.',
                ]
                
            );
            
        // END VALIDASI

        $user_login = $request->session()->get('user_login');
            
        // AMBIL TABEL PRODI DENGAN PRODI SESUAI REQUEST
        $prodi = Prodi::where('nama_prodi', $request->program_studi)->first();

        // AMBIL TABEL DOSEN BERDASARKAN NAMA PRODI
        $dosen = Dosen::where('program_studi', $prodi->nama_prodi)->get();
        
        // VALIDASI DOSEN MAX 999 ORANG
        if (count($dosen) >= 999) {
            return redirect('/managekuliah/managedosen')->with('status', 'Dosen Maksimal 999!');
        }
        
        /* GENERATE KODE DOSEN */
        $request_kode_dosen = "";

        //  jika dosen masih kosong
        if (count($dosen) == 0) {
            $request_kode_dosen = $prodi->kode_prodi."001";
        }

        // ambil panjang kode prodi (2 / 3)
        $kode_prodi_length = strlen($prodi->kode_prodi);

        // looping keseluruhan dosen berdasarkan request prodi
        for ($i=0; $i < count($dosen); $i++) { 

            // jika kode dosen tidak sama dengan iterasi looping + 1 (yang berarti ada kode yang masih bisa di pakai)
            if(substr($dosen[$i]->kode_dosen,$kode_prodi_length) != ($i+1)) {

                // generate angka kode dosen
                $last_numb = 0;
                $index_len = strlen($i+1);
                
                // jika iterasi masih satuan
                if($index_len == 1) $last_numb = "00".($i+1);
                // jika iterasi sudah puluhan
                elseif ($index_len == 2) $last_numb = "0".($i+1);
                // jika iterasi sudah ratusan
                else $last_numb = $i+1;
                
                // concat kode dosen = kode prodi(IF) + $last_numb = IF001
                $request_kode_dosen = substr($dosen[$i]->kode_dosen,0,$kode_prodi_length).$last_numb;

                // akhiri loop jika sudah berhasil
                break 1;
            }   
        }
                
        // jika kode dosen masih belum didapatkan (berarti seluruh dosen sudah dilooping dan tidak ada kode yang bisa dipakai, maka tambah 1 akhir dari kode yang ada)
        if(!$request_kode_dosen){
            $request_kode_dosen = ++$dosen[count($dosen)-1]->kode_dosen;
        }

        if ($user_login->role_id != '1'){
            // INSERT DATA KE Request dosen
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Dosen',
                'kode_manage' => $request_kode_dosen.'-'.$request->nidn,
                'nama_manage' => strtolower($request->nama),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> '',
                'nama_prodi'=> strtolower($request->program_studi),
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU DOSEN
            return redirect('/managekuliah/managedosen')->with('status', 'Data dosen Berhasil dikirimkan ke admin!');

        } else {   
        
            // INSERT DATA KE TABEL DOSEN
            DB::table('dosen')->insert([
                'kode_dosen' => $request_kode_dosen,
                'nidn' => $request->nidn,
                'nama' => strtolower($request->nama),
                'program_studi' => $request->program_studi,
            ]);

            // RETURN REDIRECT KE HALAMAN MENU DOSEN
            return redirect('/managekuliah/managedosen')->with('status', 'Data dosen Berhasil Ditambahkan!');
        }
    }

    public function edit(Request $request, $kode_dosen)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        $dosen = DB::table('dosen')->where('kode_dosen',$kode_dosen)->first();
        $prodi = DB::table('prodi')->get();
        
        return view('managedosen.edit', compact('user_login', 'dosen','prodi','countRequest'));
    }

    public function update(Request $request, $kode_dosen)
    {

        // VALIDASI FORM
        $request->validate(
            [
                'nama' => 'required|min:3|max:255',
                'nidn' => 'required',
                'program_studi' => 'required'
            ],
            [
                'nama.required' => 'Kolom nama harap di isi.',
                'nama.min' => 'Nama minimal 3 huruf.',
                'nama.max' => 'Nama minimal 255 huruf.',
                'nidn.required' => 'NIDN Atau NIP Tidak Boleh Kosong.',
                'program_studi.required' => 'Harap pilih salah satu program studi.',
                ]
                
            );
        // END VALIDASI FORM

        $user_login = $request->session()->get('user_login');

        // AMBIL TABEL PRODI DENGAN NAMA PRODI SESUAI REQUEST
        $prodi = DB::table('prodi')->where('nama_prodi', $request->program_studi)->first();

        // AMBIL DATA LAMA DOSEN YANG DI EDIT
        $dosen_old = DB::table('dosen')->where('kode_dosen',$kode_dosen)->first();

        // CEK APAKAH PROGRAM STUDI YANG LAMA TIDAK SAMA DENGAN YANG DI REQUEST
        if($dosen_old->program_studi != $request->program_studi){

            // AMBIL TABLE DOSEN DENGAN PRODI BERDASARKAN REQUEST
            $allDosen = DB::table('dosen')->where('program_studi', $prodi->nama_prodi)->get();
            
            // VALIDASI DOSEN MAX 999 ORANG
            if (count($allDosen) >= 999) {
                return redirect('/managekuliah/managedosen')->with('status', 'Dosen Maksimal 999!');
            }

            // GENERATE KODE DOSEN
            $request_kode_dosen = "";
    
            //  jika dosen masih kosong
            if (count($allDosen) == 0) {
                $request_kode_dosen = $prodi->kode_prodi."001";
            }

            // ambil panjang kode prodi (2 / 3)
            $kode_prodi_length = strlen($prodi->kode_prodi);
            
            // looping keseluruhan dosen berdasarkan request prodi
            for ($i=0; $i < count($allDosen); $i++) { 

                // jika kode dosen tidak sama dengan iterasi looping + 1 (yang berarti ada kode yang masih bisa di pakai)
                if(substr($allDosen[$i]->kode_dosen,$kode_prodi_length) != ($i+1)) {

                    // generate angka kode dosen
                    $last_numb = 0;
                    $index_len = strlen($i+1);
                    
                    // jika iterasi masih satuan
                    if($index_len == 1) $last_numb = "00".($i+1);
                    // jika iterasi sudah puluhan
                    elseif ($index_len == 2) $last_numb = "0".($i+1);
                    // jika iterasi sudah ratusan
                    else $last_numb = $i+1;
                    
                    // concat kode dosen = kode prodi(IF) + $last_numb = IF001
                    $request_kode_dosen = substr($allDosen[$i]->kode_dosen,0,$kode_prodi_length).$last_numb;

                    // akhiri loop jika sudah berhasil
                    break 1;
                }   
            }
            
            // jika kode dosen masih belum didapatkan (berarti seluruh dosen sudah dilooping dan tidak ada kode yang bisa dipakai, maka tambah 1 akhir dari kode yang ada)
            if(!$request_kode_dosen){
                $request_kode_dosen = ++$allDosen[count($allDosen)-1]->kode_dosen;
            }
        
        // JIKA PRODI LAMA MASIH SAMA DENGAN YANG DIREQUEST
        } else {
            $request_kode_dosen = $dosen_old->kode_dosen;
        }

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL DOSEN
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Dosen',
                'kode_manage' => $request_kode_dosen.'-'.$dosen_old->kode_dosen.'-'.$request->nidn,
                'nama_manage' => strtolower($request->nama),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> '',
                'nama_prodi'=> strtolower($request->program_studi),
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managedosen')->with('status', 'Perubahan Berhasil diajukan ke admin!');

        } else {   

            // INSERT EDIT DATA KE TABEL DOSEN
            DB::table('dosen')
            ->where('kode_dosen', $kode_dosen)
            ->update([
                'kode_dosen' => $request_kode_dosen,
                'nama' => strtolower($request->nama),
                'nidn' => $request->nidn,
                'program_studi' => $request->program_studi,
            ]);

            // UPDATE NAMA DOSEN PENGAJAR DI TABEL KELAS
            DB::table('kelas')
            ->where('nama_dosen', $dosen_old->nama)
            ->update([
                'nama_dosen' => strtolower($request->nama),
            ]);

            // RETURN KE HALAMAN MANAGE DOSEN
            return redirect('/managekuliah/managedosen')->with('status', 'Data dosen berhasil diubah');

        }
    }


    public function destroy(Request $request, $kode_dosen)
    {
        $user_login = $request->session()->get('user_login');

        $all_dosen = DB::table('dosen')->get();

        if(count($all_dosen) == 1) {
            return redirect('managekuliah/managedosen')->with('status', 'Minimal Tersisa Satu Dosen!');
        }

        // AMBIL DATA MATKUL SEBELUM DI DELETE
        $dosen = Dosen::where('kode_dosen',$kode_dosen)->first();

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST DOSEN
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Dosen',
                'kode_manage' => $kode_dosen.'-'.$dosen->nidn,
                'nama_manage' => strtolower($dosen->nama),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> '',
                'nama_prodi'=> strtolower($dosen->program_studi),
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managedosen')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }

        DB::table('dosen')->where('kode_dosen', $kode_dosen)->delete();
        return redirect('/managekuliah/managedosen')->with('status', 'Data dosen berhasil dihapus!');
    }
}
