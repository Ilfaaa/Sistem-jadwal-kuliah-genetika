<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;

class ManagekelasController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $request_keyword = "";
        if($request->keyword){
            $kelas = DB::table('kelas')->where('nama_matkul', 'LIKE', "%{$request->keyword}%")->orWhere('nama_dosen', 'LIKE', "%{$request->keyword}%")->orWhere('kelas', 'LIKE', "%{$request->keyword}%")->orWhere('kapasitas_kelas', 'LIKE', "%{$request->keyword}%")->get();
            $request_keyword = $request->keyword;
        } else {
            $kelas = DB::table('kelas')->get();
        }

        // list tahun ajaran yang ada
        $tahun_ajaran = DB::table('tahun_ajaran')->get();

        $kelasByTahun = [];
        foreach($tahun_ajaran as $tahun) {
            array_push($kelasByTahun, [$tahun->tahun_ajaran]);
        }
        
        for ($i=0; $i < count($kelasByTahun); $i++) { 
            $tempKelas = DB::table('kelas')->where('tahun_ajaran', $kelasByTahun[$i])->get();
            if ($tempKelas) {
                array_push($kelasByTahun[$i], $tempKelas);
            } else {
                array_push($kelasByTahun[$i], []);
            }
        }

        return view('managekelas.index', compact('kelas', 'user_login','request_keyword','countRequest','kelasByTahun'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        // list tahun ajaran yang ada
        $tahun_ajaran = DB::table('tahun_ajaran')->get();
        
        $semester = DB::table('semester')->get();
        $matkul = DB::table('matkul')->get();
        $dosen = DB::table('dosen')->get();
        $prodi = DB::table('prodi')->get();
        return view('managekelas.create', compact('user_login','semester','prodi','matkul','dosen','countRequest','tahun_ajaran'));
    }

    public function create_action(Request $request) {

        if($request->ajax()) {
            if($request->has('prodi')){

                $prodi = $request->get('prodi');
                $tahun_ajaran = $request->get('tahun_ajaran');
                $prodi = explode("-",$prodi);
                
                $dosenByProdi = DB::table('dosen')->where('program_studi', $prodi[1])->get();
                $matkulByProdiAndTahunAjaran = DB::table('matkul')->where('kode_prodi', $prodi[0])->where('tahun_ajaran',$tahun_ajaran)->get();
                
                $data = array(
                    'allDosen'  => $dosenByProdi,
                    'allMatkul'  => $matkulByProdiAndTahunAjaran,
                );
                echo json_encode($data);
            }
            if($request->has('matkul')){

                $matkul = $request->get('matkul');
                $tahun_ajaran = $request->get('tahun_ajaran');
                $matkul = explode("-",$matkul);
                
                $kelasByMatkulAndTahunAjaran = DB::table('kelas')->where('nama_matkul', $matkul[1])->where('tahun_ajaran', $tahun_ajaran)->get();
                
                $data = array(
                    'kelas'  => $kelasByMatkulAndTahunAjaran
                );
                echo json_encode($data);
            }
        } else {
            return redirect('/managekuliah/managekelas/create');
        }
    }
    

    public function store(Request $request)
    {
        
        $request->validate(
            [
                'prodi' => 'required|min:3|max:255',
                'matkul' => 'required|min:3|max:255',
                'dosen_pengajar' => 'required|min:3|max:255',
                'kelas' => 'required',
                'kapasitas_kelas' => 'required|numeric|min:1|max:100',
            ],
            [
                'prodi.required' => 'Harap Pilih Program Studi.',
                'prodi.min' => 'Program Studi minimal 3 huruf.',
                'prodi.max' => 'Program Studi minimal 255 huruf.',
                'matkul.required' => 'Harap Pilih Mata Kuliah.',
                'matkul.min' => 'Mata Kuliah minimal 3 huruf.',
                'matkul.max' => 'Mata Kuliah minimal 255 huruf.',
                'dosen_pengajar.required' => 'Harap Pilih Dosen Pengajar.',
                'dosen_pengajar.min' => 'Nama Dosen minimal 3 huruf.',
                'dosen_pengajar.max' => 'Nama Dosen minimal 255 huruf.',
                'kapasitas_kelas.required' => 'Harap Pilih Kapasitas Kelas.',
                'kapasitas_kelas.numeric' => 'Kapasitas Kelas Harus Berupa Angka',
                'kapasitas_kelas.min' => 'Kapasitas Kelas Minimal 1 Orang',
                'kapasitas_kelas.max' => 'Kapasitas Kelas maksimal 100 Orang',
                'kelas.required' => 'Seluruh Kelas Sudah Terpenuhi.',
            ]
            
        );

        // dump($request->all());

        $matkul = explode("-",$request->matkul); // TIF0001 - Basisdata

        // dd($request->all());

        // // Validasi Kelas Exist //
        
        // $kelasIsExist = Kelas::where('nama_matkul',$matkul[1])->get();

        // if (count($kelasIsExist) == 0){
        //     $kelas = 'A';
        // }
        // if (count($kelasIsExist) == 1){
        //     if ($kelasIsExist[0]->kelas == 'A'){
        //         $kelas = 'B';
        //     } else {
        //         $kelas = 'A';
        //     }
        // }
        // if(count($kelasIsExist) == 2) {
        //     return redirect('/managekuliah/managekelas/create')->with('kelas_exist', "Kelas A dan B pada ".ucwords($matkul[1])." sudah ada!");
        // } 
        
        // // End Validasi Kelas Exist //
        $kelas = $request->kelas;

        $user_login = $request->session()->get('user_login');


        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST KULIAH
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Kelas',
                'kode_manage' => $matkul[0].$kelas, // TIF0001A
                'nama_manage' => strtoupper($kelas), // A
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> $request->tahun_ajaran, //tahun ajaran numpang data di kode_semester
                'nama_prodi'=> '',
                'nama_matkul'=> strtolower($matkul[1]), //basisdata
                'nama_dosen'=> $request->dosen_pengajar,
                'kapasitas_kelas'=> $request->kapasitas_kelas,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU DOSEN
            return redirect('/managekuliah/managekelas')->with('status', 'Data kelas Berhasil dikirimkan ke admin!');

        } else {  
        
            DB::table('kelas')->insert([
                'kode_kelas' => $matkul[0].$kelas, // TIF0001A
                'nama_matkul' => strtolower($matkul[1]), // basisdata
                'nama_dosen' => $request->dosen_pengajar, // rizalul akram
                'kelas' => strtoupper($kelas), // A
                'kapasitas_kelas'=> $request->kapasitas_kelas, // 40
                'tahun_ajaran' => $request->tahun_ajaran, // 2024/2025
            ]);

            $kuliah = DB::table('kuliah')->where('tahun_ajaran', $request->tahun_ajaran)->get();

            // dump($kuliah);


            // insert ke Table Kuliah
            $kode_kuliah = (count($kuliah) == 0) ? 1 : $kuliah[count($kuliah) - 1]->kode_kuliah + 1;

            $kode_matkul = substr($matkul[0].$kelas, 0, -1);

            $kode_dosen = DB::table('dosen')->where('nama', $request->dosen_pengajar)->first()->kode_dosen;

            $kode_prodi = substr($matkul[0].$kelas, 0, -5);

            $kode_semester = DB::table('matkul')->where('nama_matkul', strtolower($matkul[1]))->where('tahun_ajaran',$request->tahun_ajaran)->first()->kode_semester;

            // dump($kode_kuliah);
            // dump($kode_matkul);
            // dump($kode_dosen);
            // dump($kode_prodi);
            // dump($kode_semester);



                DB::table('kuliah')->insert([
                    'kode_kuliah' => $kode_kuliah, // 1
                    'kode_matkul' => $kode_matkul, //TIF0001
                    'kode_dosen' => $kode_dosen, //TIF001
                    'kode_kelas' => $matkul[0].$kelas, //TIF0001A
                    'kode_prodi' => $kode_prodi, // TIF
                    'kode_semester' => $kode_semester, // 2
                    'tahun_ajaran' => $request->tahun_ajaran, //2023/2025
                ]);

            $kuliah = DB::table('kuliah')->where('tahun_ajaran', $request->tahun_ajaran)->get();

                // dd($kuliah);
            

                if($kuliah[count($kuliah) - 1]->kode_kuliah != count($kuliah)) {
                    for ($i=0; $i < count($kuliah); $i++) { 
                        DB::table('kuliah')
                        ->where('kode_kuliah', $kuliah[$i]->kode_kuliah)
                        ->where('tahun_ajaran', $request->tahun_ajaran)
                        ->update([
                            'kode_kuliah' => $i+1,
                        ]);
                    }
                }
            return redirect('/managekuliah/managekelas')->with('status', 'Data kelas Berhasil Ditambahkan!');
        }
    }

    public function edit(Request $request, $kode_kelas, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        $tahun_ajaran_temp = explode('-', $tahun_ajaran); 
        $tahun_ajaran = implode('/',$tahun_ajaran_temp);
        
        $kelas = DB::table('kelas')->where('kode_kelas',$kode_kelas)->where('tahun_ajaran', $tahun_ajaran)->first();

        $currentDosen = $kelas->nama_dosen;
        $dosenProdi = DB::table('dosen')->where('nama',$currentDosen)->first();
        $allDosenByProdi = DB::table('dosen')->where('program_studi',$dosenProdi->program_studi)->get();

        $tahun_ajaran_temp = explode('/', $tahun_ajaran); 
        $tahun_ajaran = implode('-',$tahun_ajaran_temp);

        return view('managekelas.edit', compact('user_login', 'kelas','allDosenByProdi','countRequest','tahun_ajaran'));
    }

    public function update(Request $request, $kode_kelas, $tahun_ajaran)
    {


        $request->validate(
            [

                'dosen_pengajar' => 'required|min:3|max:255',
                'kapasitas_kelas' => 'required|numeric|min:1|max:100',
            ],
            [
                'dosen_pengajar.required' => 'Harap Pilih Dosen Pengajar.',
                'dosen_pengajar.min' => 'Nama Dosen minimal 3 huruf.',
                'dosen_pengajar.max' => 'Nama Dosen minimal 255 huruf.',
                'kapasitas_kelas.required' => 'Harap Pilih Kapasitas Kelas.',
                'kapasitas_kelas.numeric' => 'Kapasitas Kelas Harus Berupa Angka',
                'kapasitas_kelas.min' => 'Kapasitas Kelas Minimal 1 Orang',
                'kapasitas_kelas.max' => 'Kapasitas Kelas maksimal 100 Orang',
            ]
            
        );

        $tahun_ajaran_temp = explode('-', $tahun_ajaran); 
        $tahun_ajaran = implode('/',$tahun_ajaran_temp);

        $kelas = Kelas::where('kode_kelas', $kode_kelas)->where('tahun_ajaran', $tahun_ajaran)->first();

        $user_login = $request->session()->get('user_login');

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST KULIAH
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Kelas',
                'kode_manage' => $kode_kelas,
                'nama_manage' => $kelas->kelas,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> $tahun_ajaran, // numpang tahun ajaran di kode_semester
                'nama_prodi'=> strtolower($request->program_studi),
                'nama_matkul'=> $kelas->nama_matkul,
                'nama_dosen'=> $request->dosen_pengajar,
                'kapasitas_kelas'=> $request->kapasitas_kelas,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managekelas')->with('status', 'Perubahan Berhasil diajukan ke admin!');

        } else { 

            DB::table('kelas')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'nama_dosen' => $request->dosen_pengajar,
                'kapasitas_kelas'=> $request->kapasitas_kelas,
            ]);

            $kode_dosen = DB::table('dosen')->where('nama', $request->dosen_pengajar)->first()->kode_dosen;

            DB::table('kuliah')
            ->where('kode_kelas', $kode_kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'kode_dosen' => $kode_dosen,
            ]);

            DB::table('jadwal')
            ->where('matkul', $kelas->nama_matkul)
            ->where('kelas', $kelas->kelas)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->update([
                'dosen' => $request->dosen_pengajar
            ]);

            return redirect('/managekuliah/managekelas')->with('status', 'Data kelas berhasil diubah');

        }
    }


    public function destroy($kode_kelas, Request $request, $tahun_ajaran)
    {
        $user_login = $request->session()->get('user_login');

        $tahun_ajaran_temp = explode('-', $tahun_ajaran); 
        $tahun_ajaran = implode('/',$tahun_ajaran_temp);

        $all_kelas = DB::table('kelas')->where('tahun_ajaran',$tahun_ajaran)->get();

        if(count($all_kelas) == 1) {
            return redirect('managekuliah/managekelas')->with('status', 'Minimal Tersisa Satu Kelas!');
        }

        // Ambil data sebelum di delete
        $kelas = Kelas::where('kode_kelas',$kode_kelas)->where('tahun_ajaran',$tahun_ajaran)->first();

        if ($user_login->role_id != '1'){
            // INSERT DATA KE TABEL REQUEST DOSEN
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Kelas',
                'kode_manage' => $kode_kelas,
                'nama_manage' => $kelas->kelas,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester'=> $tahun_ajaran, // numpang tahun_ajaran di kode_semester
                'nama_prodi'=> '',
                'nama_matkul'=> $kelas->nama_matkul,
                'nama_dosen'=> $kelas->nama_dosen,
                'kapasitas_kelas'=> $kelas->kapasitas_kelas,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d h:i:s")
            ]);

            // RETURN REDIRECT KE HALAMAN MENU MATKUL
            return redirect('/managekuliah/managekelas')->with('status', 'Hapus Data Berhasil diajukan ke admin!');
        }

        DB::table('kelas')->where('kode_kelas', $kode_kelas)->where('tahun_ajaran',$tahun_ajaran)->delete();
        DB::table('kuliah')->where('kode_kelas', $kode_kelas)->where('tahun_ajaran',$tahun_ajaran)->delete();

        return redirect('/managekuliah/managekelas')->with('status', 'Data kelas berhasil dihapus!');
    }
}
