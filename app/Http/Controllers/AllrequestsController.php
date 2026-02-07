<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Kuliah;
use App\Models\Matkul;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Waktu;
use App\Models\Jam;
use App\Models\Hari;

class AllrequestsController extends Controller
{
    public function index(Request $request) {

        $user_login = $request->session()->get('user_login');
        $request_kuliah = DB::table('request_kuliah')->get();
        $request_ruang = DB::table('request_ruang')->get();
        $request_waktu = DB::table('request_waktu')->get();
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();


        return view('allrequests.index',compact('user_login','request_kuliah','request_ruang','request_waktu', 'countRequest'));
    }
    public function acceptKuliah(Request $request, $id){
        $request = DB::table('request_kuliah')->where('id', $id)->first();


        if($request->manage == 'Mata Kuliah'){
            $allMatkul = Matkul::get();
            if($request->request == 'Tambah Data'){

                // Jika Kode Prodi Sudah Ada
                $matkulByProdi = [];
                foreach($allMatkul as $matkul){
                    if($request->kode_prodi == $matkul->kode_prodi) {
                        array_push($matkulByProdi, $matkul->kode_matkul);
                    }
                }
                
                if (in_array($request->kode_manage, $matkulByProdi))
                {
                    $newKode = last($matkulByProdi);
                    $request->kode_manage = ++$newKode;
                }

                $the_kode_semester = explode(":",$request->kode_semester)[0];
                $perkuliahan_semester = explode(":",$request->kode_semester)[1];
                $tahun_ajaran = explode(":",$request->kode_semester)[2];

                // INSERT DATA KE TABEL MATKUL
                $matkul = new Matkul;
                $matkul->kode_matkul = $request->kode_manage;
                $matkul->nama_matkul = $request->nama_manage;
                $matkul->sks = $request->sks;
                $matkul->kode_prodi = $request->kode_prodi;
                $matkul->kode_semester = $the_kode_semester;
                $matkul->perkuliahan_semester = $perkuliahan_semester;
                $matkul->tahun_ajaran = $tahun_ajaran;
                $matkul->save();

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Mata Kuliah berhasil ditambahkan');
            }
            if($request->request == 'Ubah Data'){

                $the_kode_semester = explode(":",$request->kode_semester)[0];
                $perkuliahan_semester = explode(":",$request->kode_semester)[1];
                $tahun_ajaran = explode(":",$request->kode_semester)[2];

                $matkul =  Matkul::where('kode_matkul', $request->kode_manage)->where('tahun_ajaran', $tahun_ajaran)->first();



                // UPDATE TABEL MATKUL
                Matkul::where('kode_matkul', $request->kode_manage)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->update([
                    'nama_matkul' => $request->nama_manage,
                    'sks' => $request->sks,
                    'kode_semester' => $the_kode_semester,
                    'perkuliahan_semester' => $perkuliahan_semester,
                ]);

                // UPDATE NAMA MATKUL DI TABEL KELAS
                Kelas::where('nama_matkul',$matkul->nama_matkul)
                ->where('tahun_ajaran',$tahun_ajaran)
                ->update([
                    'nama_matkul' => strtolower($request->nama_manage)
                ]);

                // UPDATE SEMESTER MATKUL DI TABEL KULIAH
                Kuliah::where('kode_matkul',$matkul->kode_matkul)
                ->where('tahun_ajaran',$tahun_ajaran)
                ->update([
                    'kode_semester' => $the_kode_semester
                ]);

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Mata Kuliah berhasil diubah');
            }
            if($request->request == 'Hapus Data'){

                $the_kode_semester = explode(":",$request->kode_semester)[0];
                $perkuliahan_semester = explode(":",$request->kode_semester)[1];
                $tahun_ajaran = explode(":",$request->kode_semester)[2];

                $all_matkul = Matkul::where('tahun_ajaran',$tahun_ajaran)->get();


                if(count($all_matkul) == 1) {
                    DB::table('request_kuliah')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Matkul!');
                }

                // DELETE TABEL MATKUL
                $deleteMatkul = Matkul::where('kode_matkul',$request->kode_manage)->where('tahun_ajaran',$tahun_ajaran)->delete();

                // DELETE KELAS DENGAN MATKUL TERSEBUT
                Kelas::where('nama_matkul',$request->nama_manage)->where('tahun_ajaran',$tahun_ajaran)->delete();

                // DELETE KULIAH DENGAN MATKUL TERSEBUT 
                Kuliah::where('kode_matkul',$request->kode_manage)->where('tahun_ajaran',$tahun_ajaran)->delete();
            
                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // JIKA MATKUL TIDAK ADA
                if(!$deleteMatkul){
                    return redirect('/allrequests')->with('status', 'Mata Kuliah sudah tidak ada');
                }

                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Mata Kuliah berhasil dihapus');
            }
        }

        if($request->manage == 'Dosen'){
            $allDosen = Dosen::get();
            if($request->request == 'Tambah Data'){

                // pisahkan kode dosen dengan nidn
                $dosen_code = explode('-',$request->kode_manage)[0];
                $nidn_code = explode('-',$request->kode_manage)[1];

                // Jika Kode Dosen Sudah Ada
                $dosenByProdi = [];
                foreach($allDosen as $dosen){
                    if( substr($dosen_code, 0, -3) == substr($dosen->kode_dosen, 0, -3)) {
                        array_push($dosenByProdi, $dosen->kode_dosen);
                    }
                }
                
                if (in_array($dosen_code, $dosenByProdi))
                {
                    $newKode = last($dosenByProdi);
                    $dosen_code = ++$newKode;
                }

                // INSERT DATA KE TABEL DOSEN
                $dosen = new Dosen;
                $dosen->kode_dosen = $dosen_code;
                $dosen->nidn = $nidn_code;
                $dosen->nama = $request->nama_manage;
                $dosen->program_studi = $request->nama_prodi;
                $dosen->save();

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU DOSEN
                return redirect('/allrequests')->with('status', 'Dosen berhasil ditambahkan');
            }
            if($request->request == 'Ubah Data'){

                $kode_baru = explode('-',$request->kode_manage)[0];
                $kode_lama = explode('-',$request->kode_manage)[1];
                $nidn_code = explode('-',$request->kode_manage)[2];
                $dosen =  Dosen::where('kode_dosen',$kode_lama)->first();

                // UPDATE TABEL DOSEN
                Dosen::where('kode_dosen',$kode_lama)->update([
                    'kode_dosen' => $kode_baru,
                    'nidn' => $nidn_code,
                    'nama' => $request->nama_manage,
                    'program_studi' => $request->nama_prodi,
                ]);

                // HAPUS DOSEN DI TABLE KELAS DAN KULIAH JIKA PRODI DIUBAH
                if( $kode_lama != $kode_baru ){

                    // error gagal meghapus tabel kelas dan kuliah
                    Kelas::where('nama_dosen',$dosen->nama)->delete();
                    Kuliah::where('kode_dosen',$kode_lama)->delete();

                } else {

                    // UPDATE NAMA DOSEN DI TABEL KELAS
                    Kelas::where('nama_dosen',$dosen->nama)->update([
                        'nama_dosen' => strtolower($request->nama_manage)
                    ]);

                }

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU DOSEN
                return redirect('/allrequests')->with('status', 'Dosen berhasil diubah');
            }
            if($request->request == 'Hapus Data'){

                $all_dosen = Dosen::get();

                if(count($all_dosen) == 1) {
                    DB::table('request_kuliah')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Dosen!');
                }

                $kode_dosen = explode('-',$request->kode_manage)[0];

                // DELETE TABEL DOSEN
                $deleteDosen = Dosen::where('kode_dosen',$kode_dosen)->delete();

                // DELETE TABEL KELAS YANG DIAJAR DOSEN
                Kelas::where('nama_dosen',$request->nama_manage)->delete();

                // DELETE TABEL KULIAH YANG DIAJAR DOSEN
                Kuliah::where('kode_dosen',$kode_dosen)->delete();
                
                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // DOSEN TIDAK ADA
                if(!$deleteDosen){
                    return redirect('/allrequests')->with('status', 'Dosen sudah tidak ada');
                }

                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Dosen berhasil dihapus');
            }
        }

        if($request->manage == 'Kelas'){  

            $tahun_ajaran = $request->kode_semester;

            if($request->request == 'Tambah Data'){



                // Jika Kode Kelas Sudah Ada
                $kelas = Kelas::where('kode_kelas', $request->kode_manage)->where('tahun_ajaran', $tahun_ajaran)->first();
                if ($kelas != null) {
                    // DELETE REQUEST
                    DB::table('request_kuliah')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN MENU KELAS
                    return redirect('/allrequests')->with('status', 'kelas sudah ada');
                }

                // INSERT DATA KE TABEL KELAS
                $kelas = new Kelas;
                $kelas->kode_kelas = $request->kode_manage;
                $kelas->nama_matkul = $request->nama_matkul;
                $kelas->nama_dosen = $request->nama_dosen;
                $kelas->kelas = $request->nama_manage;
                $kelas->kapasitas_kelas = $request->kapasitas_kelas;
                $kelas->tahun_ajaran = $tahun_ajaran; 
                $kelas->save();

                // INSERT INTO KULIAH TABLE
                $kuliah = Kuliah::where('tahun_ajaran', $tahun_ajaran)->get();

                $kode_kuliah = $kuliah[count($kuliah) - 1]->kode_kuliah + 1;

                $kode_matkul = Matkul::where('nama_matkul',$request->nama_matkul)->where('tahun_ajaran', $tahun_ajaran)->first()->kode_matkul;

                $kode_dosen = Dosen::where('nama', $request->nama_dosen)->first()->kode_dosen;

                $kode_prodi = substr($request->kode_manage, 0, -5);

                $kode_semester = Matkul::where('nama_matkul', $request->nama_matkul)->where('tahun_ajaran', $tahun_ajaran)->first()->kode_semester;

                // INSERT DATA KE TABEL KULIAH
                $kuliah = new Kuliah;
                $kuliah->kode_kuliah = $kode_kuliah;
                $kuliah->kode_matkul = $kode_matkul;
                $kuliah->kode_dosen = $kode_dosen;
                $kuliah->kode_kelas = $request->kode_manage;
                $kuliah->kode_prodi = $kode_prodi;
                $kuliah->kode_semester = $kode_semester;
                $kuliah->tahun_ajaran = $tahun_ajaran;
                $kuliah->save();

                $kuliah = Kuliah::where('tahun_ajaran', $tahun_ajaran)->get();
                
                if($kuliah[count($kuliah) - 1]->kode_kuliah != count($kuliah)) {
                    for ($i=0; $i < count($kuliah); $i++) { 
                    Kuliah::where('kode_kuliah',$kuliah[$i]->kode_kuliah)->update([
                        'kode_kuliah' => $i+1
                    ]);
                    }
                }

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU KELAS
                return redirect('/allrequests')->with('status', 'kelas berhasil ditambahkan');
            }
            if($request->request == 'Ubah Data'){

                // Jika Kelas Sudah Tak Ada
                $kelas = Kelas::where('kode_kelas', $request->kode_manage)->where('tahun_ajaran', $tahun_ajaran)->first();
                if ($kelas === null) {
                    // DELETE REQUEST
                    DB::table('request_kuliah')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN MENU KELAS
                    return redirect('/allrequests')->with('status', 'kelas sudah tidak ada');
                }

                // UPDATE TABEL KELAS
                Kelas::where('kode_kelas',$request->kode_manage)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->update([
                    'nama_dosen' => $request->nama_dosen,
                    'kapasitas_kelas' => $request->kapasitas_kelas,
                ]);

                // UPDATE KODE DOSEN IN KULIAH TABLE

                $kode_dosen = Dosen::where('nama', $request->nama_dosen)->first()->kode_dosen;

                Kuliah::where('kode_kelas', $request->kode_manage)->where('tahun_ajaran', $tahun_ajaran)->update([
                    'kode_dosen' => $kode_dosen
                ]);

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU KELAS
                return redirect('/allrequests')->with('status', 'kelas berhasil diubah');

            }
            if($request->request == 'Hapus Data'){

                $all_kelas = Kelas::where('tahun_ajaran', $tahun_ajaran)->get();

                if(count($all_kelas) == 1) {
                    DB::table('request_kuliah')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Kelas!');
                }

                // DELETE TABEL KELAS
                $deleteKelas = Kelas::where('kode_kelas',$request->kode_manage)->where('tahun_ajaran', $tahun_ajaran)->delete();

                // DELETE TABEL KULIAH BERDASARKAN KELAS
                Kuliah::where('kode_kelas',$request->kode_manage)->where('tahun_ajaran', $tahun_ajaran)->delete();
                
                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // KELAS TIDAK ADA
                if(!$deleteKelas){
                    return redirect('/allrequests')->with('status', 'Kelas sudah tidak ada');
                }

                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Kelas berhasil dihapus');

            }
        }

        if($request->manage == 'Program Studi'){
            if($request->request == 'Tambah Data'){
                // Jika Kode Prodi Sudah Ada
                $prodi = Prodi::where('kode_prodi', $request->kode_manage)->first();
                if ($prodi != null) {
                    // DELETE REQUEST
                    DB::table('request_kuliah')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN MENU KELAS
                    return redirect('/allrequests')->with('status', 'prodi sudah ada');
                }

                // INSERT DATA KE TABEL PRODI
                $prodi = new Prodi;
                $prodi->nama_prodi = $request->nama_manage;
                $prodi->kode_prodi = $request->kode_manage;
                $prodi->save();

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU PRODI
                return redirect('/allrequests')->with('status', 'prodi berhasil ditambahkan');
            }

            if($request->request == 'Ubah Data'){
                $kode_baru = explode('-', $request->kode_manage)[0];
                $kode_lama = explode('-', $request->kode_manage)[1];

                $prodi = Prodi::where('kode_prodi', $kode_lama)->first();
                $kode_prodi_length = strlen($prodi->kode_prodi);

                $dosen = Dosen::where('program_studi', $prodi->nama_prodi)->get();
                $matkul = Matkul::where('kode_prodi', $prodi->kode_prodi)->get();
                $kelas = Kelas::where('kode_kelas', 'LIKE', "%{$prodi->kode_prodi}%")->get();
                $kuliah = Kuliah::where('kode_prodi', $prodi->kode_prodi)->get();

                // Jika prodi Sudah Tak Ada
                $prodi = Prodi::where('kode_prodi', $kode_lama)->first();
                if ($prodi === null) {
                    // DELETE REQUEST
                    DB::table('request_kuliah')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN MENU PRODI
                    return redirect('/allrequests')->with('status', 'prodi sudah tidak ada');
                }

                // UPDATE TABEL PRODI
                Prodi::where('kode_prodi',$kode_lama)->update([
                    'nama_prodi' => $request->nama_manage,
                    'kode_prodi' => $kode_baru,
                ]);

                // UPDATE DOSEN BERDASARKAN PRODI BARU
                foreach ($dosen as $d) {
                    Dosen::where('kode_dosen', $d->kode_dosen)
                    ->update([
                        'kode_dosen' => strtoupper($kode_baru).substr($d->kode_dosen,$kode_prodi_length),
                        'program_studi' => strtolower($request->nama_manage),
                    ]);
                }

                // UPDATE MATKUL BERDASARKAN PRODI BARU
                foreach ($matkul as $m) {
                    Matkul::where('kode_matkul', $m->kode_matkul)
                    ->update([
                        'kode_matkul' => strtoupper($kode_baru).substr($m->kode_matkul,$kode_prodi_length),
                        'kode_prodi' => strtoupper($kode_baru),
                    ]);
                }
                
                // UPDATE KELAS BERDASARKAN PRODI BARU
                foreach ($kelas as $k) {
                    Kelas::where('kode_kelas', $k->kode_kelas)
                    ->update([
                        'kode_kelas' => strtoupper($kode_baru).substr($k->kode_kelas,$kode_prodi_length),
                    ]);
                }

                
                
                foreach ($kuliah as $k) {
                    DB::table('kuliah')
                    ->where('kode_kelas', $k->kode_kelas)
                    ->update([
                        'kode_matkul' => strtoupper($kode_baru).substr($k->kode_matkul,$kode_prodi_length),
                        'kode_dosen' => strtoupper($kode_baru).substr($k->kode_dosen,$kode_prodi_length),
                        'kode_kelas' => strtoupper($kode_baru).substr($k->kode_kelas,$kode_prodi_length),
                        'kode_prodi' => strtoupper($kode_baru)
                    ]);
                }
                
                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU KELAS
                return redirect('/allrequests')->with('status', 'Prodi berhasil diubah');
            }

            if($request->request == 'Hapus Data') {
                $all_prodi = Prodi::get();

                if(count($all_prodi) == 1) {
                    DB::table('request_kuliah')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Prodi!');
                }

                // Ambil data sebelum di delete
                $prodi = DB::table('prodi')->where('kode_prodi',$request->kode_manage)->first();
                $kode_prodi_length = strlen($prodi->kode_prodi);

                // DELETE TABEL PRODI
                $deleteProdi = Prodi::where('kode_prodi',$request->kode_manage)->delete();

                // PRODI TIDAK ADA
                if(!$deleteProdi){
                    return redirect('/allrequests')->with('status', 'Prodi sudah tidak ada');
                }

                Kuliah::where('kode_prodi', $prodi->kode_prodi)->delete();
                Matkul::where('kode_prodi', $prodi->kode_prodi)->delete();
                Dosen::where('program_studi', $prodi->nama_prodi)->delete();

                $kelas = Kelas::get();
                foreach ($kelas as $k) {
                    if(substr($k->kode_kelas,0,$kode_prodi_length) == $prodi->kode_prodi){
                        Kelas::where('kode_kelas', $k->kode_kelas)->delete();
                    }
                }

                // DELETE REQUEST
                DB::table('request_kuliah')->where('id', $request->id)->delete();

                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Prodi berhasil dihapus');
            }
        }
        
    }

    public function rejectKuliah($id){

        // DELETE MATKUL 
        DB::table('request_kuliah')->where('id', $id)->delete();
        return redirect('/allrequests')->with('status', 'Request ditolak');
    }

    public function acceptRuang(Request $request, $id){

        $request = DB::table('request_ruang')->where('id', $id)->first();

        if($request->request == 'Tambah Data'){
            // Jika Kode Ruang Sudah Ada
            $ruang = Ruang::where('kode_ruang', $request->kode_ruang)->first();
            if ($ruang != null) {
                // DELETE REQUEST
                DB::table('request_ruang')->where('id', $request->id)->delete();

                // RETURN REDIRECT KE HALAMAN allrequest
                return redirect('/allrequests')->with('status', 'Ruang sudah ada');
            }

            // INSERT DATA KE TABEL RUANG
            $ruang = new Ruang;
            $ruang->kode_ruang = $request->kode_ruang;
            $ruang->nama_ruang = $request->nama_ruang;
            $ruang->nama_prodi = $request->nama_prodi;
            $ruang->save();

            // DELETE REQUEST
            DB::table('request_ruang')->where('id', $request->id)->delete();
            
            // RETURN REDIRECT KE HALAMAN MENU PRODI
            return redirect('/allrequests')->with('status', 'ruang berhasil ditambahkan');
        }

        if($request->request == 'Ubah Data'){

            // Jika Ruang Sudah Tak Ada
            $ruang = Ruang::where('kode_ruang', $request->kode_ruang)->first();
            if ($ruang === null) {
                // DELETE REQUEST
                DB::table('request_ruang')->where('id', $request->id)->delete();

                // RETURN REDIRECT KE HALAMAN MENU PRODI
                return redirect('/allrequests')->with('status', 'Ruang sudah tidak ada');
            }

            // UPDATE TABEL RUANG
            Ruang::where('kode_ruang', $request->kode_ruang )->update([
                'nama_ruang' => $request->nama_ruang,
                'nama_prodi' => $request->nama_prodi,
            ]);
            
            // DELETE REQUEST
            DB::table('request_ruang')->where('id', $request->id)->delete();
            
            // RETURN REDIRECT KE HALAMAN MENU KELAS
            return redirect('/allrequests')->with('status', 'Ruang berhasil diubah');
        }

        if($request->request == 'Hapus Data') {

            $all_ruang = Ruang::get();

            if(count($all_ruang) == 1) {
                DB::table('request_ruang')->where('id', $request->id)->delete();
                return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Ruang!');
            }

            // DELETE TABEL RUANG
            $deleteRuang = Ruang::where('kode_ruang',$request->kode_ruang)->delete();

            // Ruang TIDAK ADA
            if(!$deleteRuang){
                return redirect('/allrequests')->with('status', 'Ruang sudah tidak ada');
            }

            if($all_ruang[count($all_ruang) - 1]->kode_ruang != count($all_ruang)) {
                for ($i=0; $i < count($all_ruang); $i++) { 
                    Ruang::where('kode_ruang', $all_ruang[$i]->kode_ruang)
                    ->update([
                        'kode_ruang' => $i+1,
                    ]);
                }
            }

            // DELETE REQUEST
            DB::table('request_ruang')->where('id', $request->id)->delete();

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/allrequests')->with('status', 'Ruang berhasil dihapus');
        }
    }

    public function rejectRuang($id){

        // DELETE MATKUL 
        DB::table('request_ruang')->where('id', $id)->delete();
        return redirect('/allrequests')->with('status', 'Request ditolak');
    }

    public function acceptWaktu(Request $request, $id){
        $request = DB::table('request_waktu')->where('id', $id)->first();

        if($request->manage == 'Waktu'){
            if($request->request == 'Tambah Data'){
                // PRoblem = Jika Waktu Sudah ada?
                $hoursCode = explode('-',$request->kode_jam);
                $kode = 1;
                $waktu = Waktu::get();
                if(count($waktu) != 0){
                    if(($waktu[count($waktu)-1]->kode_waktu) != count($waktu)){
                        for ($i=0; $i < count($waktu); $i++) { 
                            Waktu::where('kode_waktu', $waktu[$i]->kode_waktu)
                            ->update([
                                'kode_waktu' => $i + 1,
                            ]);
                        }
                        $waktu = Waktu::get();
                        $kode = $waktu[count($waktu)-1]->kode_waktu + 1;
                    } else {
                        $kode = $waktu[count($waktu)-1]->kode_waktu + 1;
                    }
                }

                foreach ($hoursCode as $hourCode) {

                    $existWaktu = Waktu::where('kode_hari', $request->kode_hari)->where('kode_jam', $hourCode)->first(); 

                    if($existWaktu == null) {
                        $waktu = new Waktu;
                        $waktu->kode_waktu = $kode++;
                        $waktu->kode_hari = $request->kode_hari;
                        $waktu->kode_jam = $hourCode;
                        $waktu->save();
                    }
                }

                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN ALL REQUESTS
                return redirect('/allrequests')->with('status', 'Waktu berhasil ditambahkan');
            }

            if($request->request == 'Hapus Data'){

                $all_waktu = Waktu::get();

                if(count($all_waktu) == 1) {
                    DB::table('request_waktu')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Waktu!');
                }

                // DELETE TABEL WAKTU
                $deleteWaktu = Waktu::where('kode_hari',$request->kode_hari)->where('kode_jam',$request->kode_jam)->delete();
            
                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();

                // JIKA WAKTU TIDAK ADA
                if(!$deleteWaktu){
                    return redirect('/allrequests')->with('status', 'Waktu sudah tidak ada');
                }

                $waktu = Waktu::get();

                if(($waktu[count($waktu)-1]->kode_waktu) != count($waktu)){
                    for ($i=0; $i < count($waktu); $i++) { 
                        Waktu::where('kode_waktu', $waktu[$i]->kode_waktu)
                        ->update([
                            'kode_waktu' => $i + 1,
                        ]);
                    }
                }
            
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Waktu berhasil dihapus');
            }
        }

        if($request->manage == 'Hari'){

            if($request->request == 'Tambah Data'){

                // Jika Hari Sudah Ada
                $hari = Hari::where('kode_hari', $request->kode_hari)->first();
                if ($hari != null) {
                    // DELETE REQUEST
                    DB::table('request_waktu')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN allrequest
                    return redirect('/allrequests')->with('status', 'Hari sudah ada');
                }

                // INSERT DATA KE TABEL Hari
                $hari = new Hari;
                $hari->kode_hari = $request->kode_hari;
                $hari->nama_hari = $request->nama_hari;
                $hari->save();

                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Hari berhasil ditambahkan');
            }
            if($request->request == 'Hapus Data'){

                $all_hari = Hari::get();

                if(count($all_hari) == 1) {
                    DB::table('request_waktu')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Hari!');
                }

                // DELETE TABEL Hari
                $deleteHari = Hari::where('kode_hari',$request->kode_hari)->delete();

                
                // DELETE WAKTU DENGAN HARI TERSEBUT
                Waktu::where('kode_hari',$request->kode_hari)->delete();
            
                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();

                // JIKA MATKUL TIDAK ADA
                if(!$deleteHari){
                    return redirect('/allrequests')->with('status', 'Hari sudah tidak ada');
                }
            
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Hari berhasil dihapus');
            }
        }
        if($request->manage == 'Jam'){

            if($request->request == 'Tambah Data'){

                // Jika Jam Sudah Ada
                $jam = Jam::where('kode_jam', $request->kode_jam)->first();
                if ($jam != null) {
                    // DELETE REQUEST
                    DB::table('request_waktu')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN allrequest
                    return redirect('/allrequests')->with('status', 'Jam sudah ada');
                }

                // INSERT DATA KE TABEL JAM
                $jam = new Jam;
                $jam->kode_jam = $request->kode_jam;
                $jam->jam = $request->jam;
                $jam->save();

                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Jam berhasil ditambahkan');
            }
            if($request->request == 'Ubah Data'){

                $kode_lama = explode('-',$request->kode_jam)[1];
                $kode_baru = explode('-',$request->kode_jam)[0];

                // Jika jam Sudah Tak Ada
                $jam = Jam::where('kode_jam', $kode_lama)->first();
                if ($jam === null) {
                    // DELETE REQUEST
                    DB::table('request_waktu')->where('id', $request->id)->delete();

                    // RETURN REDIRECT KE HALAMAN MENU jam
                    return redirect('/allrequests')->with('status', 'jam sudah tidak ada');
                }

                // UPDATE TABEL jam
                Jam::where('kode_jam',$kode_lama)->update([
                    'kode_jam' => $kode_baru,
                    'jam' => $request->jam,
                ]);

                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();
                
                // RETURN REDIRECT KE HALAMAN MENU MATKUL
                return redirect('/allrequests')->with('status', 'Jam berhasil diubah');
            }
            if($request->request == 'Hapus Data'){
                
                $all_jam = Jam::get();

                if(count($all_jam) == 1) {
                    DB::table('request_waktu')->where('id', $request->id)->delete();
                    return redirect('/allrequests')->with('status', 'Minimal Tersisa Satu Jam!');
                }

                // DELETE TABEL JAM
                $deleteJam = Jam::where('kode_jam',$request->kode_jam)->delete();
                
                // DELETE WAKTU DENGAN JAM TERSEBUT
                Waktu::where('kode_jam',$request->kode_jam)->delete();
            
                // DELETE REQUEST
                DB::table('request_waktu')->where('id', $request->id)->delete();

                // JIKA JAM TIDAK ADA
                if(!$deleteJam){
                    return redirect('/allrequests')->with('status', 'Jam sudah tidak ada');
                }
            
                // RETURN REDIRECT KE HALAMAN MENU JAM
                return redirect('/allrequests')->with('status', 'Jam berhasil dihapus');
            }
        }
    }

    public function rejectWaktu($id){

        // DELETE MATKUL 
        DB::table('request_waktu')->where('id', $id)->delete();
        return redirect('/allrequests')->with('status', 'Request ditolak');
    }
}
