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
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        // list tahun ajaran yang ada
        $tahun_ajaran = DB::table('tahun_ajaran')->get();


        $matkulByTahun = [];
        foreach($tahun_ajaran as $tahun) {
            array_push($matkulByTahun, [$tahun->tahun_ajaran]);
        }

        
        for ($i=0; $i < count($matkulByTahun); $i++) { 
            $tempMatkul = DB::table('matkul')->where('tahun_ajaran', $matkulByTahun[$i])->get();
            if ($tempMatkul) {
                array_push($matkulByTahun[$i], $tempMatkul);
            } else {
                array_push($matkulByTahun[$i], []);
            }
        }
        
        // dd($matkulByTahun);

        return view('managematkul.index', compact('user_login','countRequest','matkulByTahun'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        $semester = DB::table('semester')->get();
        $prodi = DB::table('prodi')->get();
        return view('managematkul.create', compact('user_login','semester','prodi','countRequest'));
    }
    public function store(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        
        // VALIDASI FORM
        $request->validate(
            [
                'nama_matkul' => 'required|min:3|max:255',
                'jumlah_sks' => 'required|numeric',
                'program_studi' => 'required',
                'semester' => 'required',
                'perkuliahan_semester' => 'required',
                'tahun_ajaran' => 'required',
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
                'tahun_ajaran.required' => 'Harap pilih salah satu tahun ajaran.',
            ]

        );
        // END VALIDASI

        // AMBIL TABEL PRODI DENGAN PRODI SESUAI REQUEST
        $prodi = DB::table('prodi')->where('kode_prodi', $request->program_studi)->first();

        // AMBIL TABEL MATKUL BERDASARKAN KODE PRODI dan Tahun Ajaran
        $matkul = DB::table('matkul')->where('kode_prodi', "$prodi->kode_prodi")->where('tahun_ajaran',$request->tahun_ajaran)->get();

        // VALIDASI MATKUL MAKSIMAL 9999
        if (count($matkul) >= 9999) {
            return redirect('/managekuliah/managematkul')->with('status', 'Mata Kuliah Maksimal 9999!');
        }

        // masukkan tahun ajaran ke tabel tahun_ajaran
        $tahun_ajaran_exist = DB::table('tahun_ajaran')->where('tahun_ajaran', $request->tahun_ajaran)->first();
        if(!$tahun_ajaran_exist){
            DB::table('tahun_ajaran')->insert([
                'tahun_ajaran' => $request->tahun_ajaran
            ]);
        }

        /* GENERATE KODE MATKUL */
        $request_kode_matkul = "";

        // JIKA MATKUL MASIH KOSONG
        if (count($matkul) == 0) {
            $request_kode_matkul = $request->program_studi."0001";
        }

        // ambil panjang kode prodi (2 / 3)
        $kode_prodi_length = strlen($prodi->kode_prodi);

        // looping keseluruhan matkul berdasarkan request prodi
        for ($i=0; $i < count($matkul); $i++) { 

            // jika kode matkul tidak sama dengan iterasi looping + 1 (yang berarti ada kode yang masih bisa di pakai)
            if(substr($matkul[$i]->kode_matkul,$kode_prodi_length) != ($i+1)) {

                // generate angka kode matkul
                $last_numb = 0;
                $index_len = strlen($i+1);
                
                // jika iterasi masih satuan
                if($index_len == 1) $last_numb = "000".($i+1);
                // jika iterasi masih puluhan
                elseif ($index_len == 2) $last_numb = "00".($i+1);
                // jika iterasi sudah ratusan
                elseif ($index_len == 3) $last_numb = "0".($i+1);
                // jika iterasi sudah ribuan
                else $last_numb = $i+1;
                
                // concat kode matkul = kode prodi(TIF) + $last_numb = TIF0001
                $request_kode_matkul = substr($matkul[$i]->kode_matkul,0,$kode_prodi_length).$last_numb;

                // akhiri loop jika sudah berhasil
                break 1;
            }   
        }

        // jika kode matkul masih belum didapatkan (berarti seluruh dosen sudah dilooping dan tidak ada kode yang bisa dipakai, maka tambah 1 akhir dari kode yang ada)
        if(!$request_kode_matkul){
            $request_kode_matkul = ++$matkul[count($matkul)-1]->kode_matkul;
        }

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL MATKUL
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Mata Kuliah',
                'kode_manage' => $request_kode_matkul,
                'nama_manage' => strtolower($request->nama_matkul),
                'sks' => $request->jumlah_sks,
                'kode_prodi' => $request->program_studi,
                'kode_semester'=> $request->semester.':'.$request->perkuliahan_semester.':'.$request->tahun_ajaran,
                'nama_prodi'=>'',
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managematkul')->with('status', 'Data matkul Berhasil dikirimkan ke admin!');

        } else {    
            // INSERT DATA KE TABEL MATKUL
            $matkul = new Matkul;
                $matkul->kode_matkul = $request_kode_matkul;
                $matkul->nama_matkul = strtolower($request->nama_matkul);
                $matkul->sks = $request->jumlah_sks;
                $matkul->kode_prodi = $request->program_studi;
                $matkul->kode_semester = $request->semester;
                $matkul->perkuliahan_semester = $request->perkuliahan_semester;
                $matkul->tahun_ajaran = $request->tahun_ajaran;
                $matkul->save();  
            
            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managematkul')->with('status', 'Data matkul Berhasil Ditambahkan!');
        }
    }

    public function edit(Request $request, $kode_matkul, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        $tahun_ajaran_temp = explode('-', $tahun_ajaran); 
        $tahun_ajaran = implode('/',$tahun_ajaran_temp);

        $matkul = DB::table('matkul')->where('kode_matkul',$kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->first();
        $semester = DB::table('semester')->get();

        $tahun_ajaran_temp = explode('/', $tahun_ajaran); 
        $tahun_ajaran = implode('-',$tahun_ajaran_temp);

        return view('managematkul.edit', compact('user_login', 'matkul','semester','countRequest', 'tahun_ajaran'));
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
            ],
            [
                'nama_matkul.required' => 'Kolom nama matkul harap di isi.',
                'nama_matkul.min' => 'Nama matkul minimal 3 huruf.',
                'nama_matkul.max' => 'Nama matkul minimal 255 huruf.',
                'jumlah_sks.required' => 'Harap piih jumlah sks.',
                'jumlah_sks.numeric' => 'Jumlah sks harus berupa angka.',
                'periode_semester.required' => 'Harap pilih Periode Semester.',
                'perkuliahan_semester.required' => 'Harap pilih Semester Perkuliahan.',
            ]
        );
        //END VALIDASI

        $tahun_ajaran_temp = explode('-', $tahun_ajaran); 
        $tahun_ajaran = implode('/',$tahun_ajaran_temp);

        // AMBIL DATA MATKUL SEBELUM DI EDIT
        $matkul_old = DB::table('matkul')->where('kode_matkul',$kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->first();

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST MATKUL
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Mata Kuliah',
                'kode_manage' => $kode_matkul,
                'nama_manage' => strtolower($request->nama_matkul),
                'sks' => $request->jumlah_sks,
                'kode_prodi' => $matkul_old->kode_prodi,
                'kode_semester'=> $request->periode_semester.':'.$request->perkuliahan_semester.':'.$tahun_ajaran,
                'nama_prodi'=>'',
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managematkul')->with('status', 'Perubahan Berhasil diajukan ke admin!');

        } else {  

            // INSERT DATA MATKUL
            DB::table('matkul')
            ->where('kode_matkul', $kode_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'nama_matkul' => strtolower($request->nama_matkul),
                'sks' => $request->jumlah_sks,
                'kode_semester' => $request->periode_semester,
                'perkuliahan_semester' => $request->perkuliahan_semester,
            ]);

            // UBAH NAMA MATKUL DI TABLE KELAS // ALERT!!!
            DB::table('kelas')
            ->where('nama_matkul',$matkul_old->nama_matkul)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'nama_matkul' => strtolower($request->nama_matkul),
            ]);

            // KEMBALI KE MANAGE MATKUL
            return redirect('/managekuliah/managematkul')->with('status', 'Data matkul berhasil diubah');
        }
    }


    public function destroy(Request $request ,$kode_matkul, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');

        $tahun_ajaran_temp = explode('-', $tahun_ajaran); 
        $tahun_ajaran = implode('/',$tahun_ajaran_temp);

        $all_matkul = DB::table('matkul')->where('tahun_ajaran', $tahun_ajaran)->get();

        if(count($all_matkul) == 1) {
            return redirect('managekuliah/managematkul')->with('status', 'Minimal Tersisa Satu Matkul!');
        }

        // AMBIL DATA MATKUL SEBELUM DI DELETE
        $matkul = DB::table('matkul')->where('kode_matkul',$kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->first();

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST MATKUL
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Mata Kuliah',
                'kode_manage' => $kode_matkul,
                'nama_manage' => strtolower($matkul->nama_matkul),
                'sks' => $matkul->sks,
                'kode_prodi' => $matkul->kode_prodi,
                'kode_semester'=> $matkul->kode_semester.':'.$matkul->perkuliahan_semester.':'.$tahun_ajaran,
                'nama_prodi'=>'',
                'nama_matkul'=>'',
                'nama_dosen'=>'',
                'kapasitas_kelas'=> 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managematkul')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }


        // DELETE MATKUL 
        DB::table('matkul')->where('kode_matkul', $kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();

        // DELETE KELAS DENGAN MATKUL TERSEBUT 
        DB::table('kelas')->where('nama_matkul', $matkul->nama_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();

        // DELETE KULIAH DENGAN MATKUL TERSEBUT 
        DB::table('kuliah')->where('kode_matkul', $matkul->kode_matkul)->where('tahun_ajaran', $tahun_ajaran)->delete();

        return redirect('/managekuliah/managematkul')->with('status', 'Data matkul berhasil dihapus!');
    }
}
