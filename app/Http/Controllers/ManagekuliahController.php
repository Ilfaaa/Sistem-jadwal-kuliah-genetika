<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ManagekuliahController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
// ---
        // list tahun ajaran yang ada
        $tahun_ajaran = DB::table('tahun_ajaran')->get();

        $kuliahByTahun = [];
        $detailKuliahByTahun = [];


        foreach($tahun_ajaran as $tahun) {
            array_push($kuliahByTahun, ['tahun_ajaran'=>$tahun->tahun_ajaran]);
            array_push($detailKuliahByTahun, ['tahun_ajaran'=>$tahun->tahun_ajaran]);
        }
        
        for ($i=0; $i < count($kuliahByTahun); $i++) { 
            $kelas = DB::table('kelas')->where('tahun_ajaran', $kuliahByTahun[$i]['tahun_ajaran'])->get();

            if (!$kelas) {
                array_push($kuliahByTahun, ['tabel_kuliah'=> '']);
                array_push($detailKuliahByTahun, ['tabel_kuliah'=> '']);
                continue;
            }

            $allKodeMatkul = [];
            $allKodeDosen = [];
            $allKodeProdi = [];
            $allKodeSemester = [];

            foreach ($kelas as $k) {
                array_push($allKodeMatkul, substr($k->kode_kelas, 0, -1));
    
                $kode_dosen = DB::table('dosen')->where('nama', $k->nama_dosen)->first()->kode_dosen;
                array_push($allKodeDosen, $kode_dosen);
    
                array_push($allKodeProdi, substr($k->kode_kelas, 0, -5));
    
                $kode_semester = DB::table('matkul')->where('nama_matkul', $k->nama_matkul)->where('tahun_ajaran', $kuliahByTahun[$i])->first()->kode_semester;
                array_push($allKodeSemester, $kode_semester);
            }

            $kuliah = DB::table('kuliah')->where('tahun_ajaran', $kuliahByTahun[$i])->get();

            if(count($kelas) != count($kuliah)){    
                DB::table('kuliah')->where('tahun_ajaran', $kuliahByTahun[$i])->delete();
    
                $i = 0;
                foreach ($kelas as $k) {
                    DB::table('kuliah')
                    ->where('tahun_ajaran', $kuliahByTahun[$i])
                    ->insert([
                        'kode_kuliah' => $i+1,
                        'kode_matkul' => $allKodeMatkul[$i],
                        'kode_dosen' => $allKodeDosen[$i],
                        'kode_kelas' => $k->kode_kelas,
                        'kode_prodi' => $allKodeProdi[$i],
                        'kode_semester' => $allKodeSemester[$i++],
                    ]);
                }
            }

            $kuliah = DB::table('kuliah')->where('tahun_ajaran', $kuliahByTahun[$i])->get();
            
    
            $wraperDetailKuliahByTahun = [];
            foreach ($kuliah as $k) {
                $kode_kuliah = $k->kode_kuliah;
                $matkul = DB::table('matkul')->where('kode_matkul',$k->kode_matkul)->where('tahun_ajaran', $kuliahByTahun[$i])->first()->nama_matkul;
                $dosen = DB::table('dosen')->where('kode_dosen',$k->kode_dosen)->first()->nama;
                $kelas = DB::table('kelas')->where('kode_kelas',$k->kode_kelas)->where('tahun_ajaran', $kuliahByTahun[$i])->first()->kelas;
                $prodi = DB::table('prodi')->where('kode_prodi',$k->kode_prodi)->first()->nama_prodi;
                $semester = DB::table('semester')->where('kode_semester',$k->kode_semester)->first()->nama_semester;
                
                array_push($wraperDetailKuliahByTahun, [compact(
                    'kode_kuliah',
                    'matkul',
                    'dosen',
                    'kelas',
                    'prodi',
                    'semester'
                )]);

            }
            $detailKuliahByTahun[$i]['tabel_kuliah'] = $wraperDetailKuliahByTahun;
    
            $kuliahByTahun[$i]['tabel_kuliah'] = $kuliah;

            // dump($detailKuliahByTahun);
            // die;
            // dd($kuliahByTahun);
        }

        return view('managekuliah.index', compact('kuliahByTahun','detailKuliahByTahun', 'user_login','countRequest'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $semester = DB::table('semester')->get();
        $matkul = DB::table('matkul')->get();
        $dosen = DB::table('dosen')->get();
        $prodi = DB::table('prodi')->get();
        return view('managekuliah.create', compact('user_login','semester','prodi','matkul','dosen','countRequest'));
    }

    public function create_action(Request $request) {

        if($request->ajax()) {
            if($request->has('prodi')){

                $prodi = $request->get('prodi');
                $prodi = explode("-",$prodi);
                
                $dosenByProdi = DB::table('dosen')->where('program_studi', $prodi[1])->get();
                $matkulByProdi = DB::table('matkul')->where('kode_prodi', $prodi[0])->get();
                
                $data = array(
                    'allDosen'  => $dosenByProdi,
                    'allMatkul'  => $matkulByProdi,
                );
                echo json_encode($data);
            } 
        } else {
            return redirect('/managekuliah/create');
        }
    }
    

    public function store(Request $request)
    {
        
        $request->validate(
            [

                'matkul' => 'required|min:3|max:255',
                'dosen_pengajar' => 'required|min:3|max:255',
                'kuliah' => 'required|regex:/^[a-zA-Z ]+$/',
                'kapasitas_kuliah' => 'required|numeric|min:1|max:100',
            ],
            [
                'matkul.required' => 'Harap Pilih Mata Kuliah.',
                'matkul.min' => 'Mata Kuliah minimal 3 huruf.',
                'matkul.max' => 'Mata Kuliah minimal 255 huruf.',
                'dosen_pengajar.required' => 'Harap Pilih Dosen Pengajar.',
                'dosen_pengajar.min' => 'Nama Dosen minimal 3 huruf.',
                'dosen_pengajar.max' => 'Nama Dosen minimal 255 huruf.',
                'kuliah.required' => 'Harap Piih kuliah.',
                'kuliah.regex' => 'kuliah Harus Berupa Huruf.',
                'kapasitas_kuliah.required' => 'Harap Pilih Kapasitas kuliah.',
                'kapasitas_kuliah.numeric' => 'Kapasitas kuliah Harus Berupa Angka',
                'kapasitas_kuliah.min' => 'Kapasitas kuliah Minimal 1 Orang',
                'kapasitas_kuliah.max' => 'Kapasitas kuliah maksimal 100 Orang',
            ]
            
        );

        $matkul = explode("-",$request->matkul);

        // Validasi kuliah Exist //
        
        $kuliahIsExist = DB::table('kuliah')->where('nama_matkul',$matkul[1])->get();
        
        if(count($kuliahIsExist) != 0) {
            $exist = false;
            foreach ($kuliahIsExist as $kuliah) {
                if($kuliah->kuliah == $request->kuliah) $exist = true;
            }
            if($exist) {
                return redirect('/managekuliah/create')->with('kuliah_exist', ucwords($matkul[1])." dengan kuliah $request->kuliah sudah ada!");
            }
        }

        // End Validasi kuliah Exist //
        
        DB::table('kuliah')->insert([
            'kode_kuliah' => $matkul[0].$request->kuliah,
            'nama_matkul' => strtolower($matkul[1]),
            'nama_dosen' => $request->dosen_pengajar,
            'kuliah' => strtoupper($request->kuliah),
            'kapasitas_kuliah'=> $request->kapasitas_kuliah,
        ]);

        return redirect('/managekuliah')->with('status', 'Data kuliah Berhasil Ditambahkan!');
    }

    public function edit(Request $request, $kode_kuliah)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $kuliah = DB::table('kuliah')->where('kode_kuliah',$kode_kuliah)->first();
        $currentDosen = $kuliah->nama_dosen;
        $dosenProdi = DB::table('dosen')->where('nama',$currentDosen)->first();
        $allDosenByProdi = DB::table('dosen')->where('program_studi',$dosenProdi->program_studi)->get();

        return view('managekuliah.edit', compact('user_login', 'kuliah','allDosenByProdi','countRequest'));
    }

    public function update(Request $request, $kode_kuliah)
    {

        $request->validate(
            [

                'dosen_pengajar' => 'required|min:3|max:255',
                'kapasitas_kuliah' => 'required|numeric|min:1|max:100',
            ],
            [
                'dosen_pengajar.required' => 'Harap Pilih Dosen Pengajar.',
                'dosen_pengajar.min' => 'Nama Dosen minimal 3 huruf.',
                'dosen_pengajar.max' => 'Nama Dosen minimal 255 huruf.',
                'kapasitas_kuliah.required' => 'Harap Pilih Kapasitas kuliah.',
                'kapasitas_kuliah.numeric' => 'Kapasitas kuliah Harus Berupa Angka',
                'kapasitas_kuliah.min' => 'Kapasitas kuliah Minimal 1 Orang',
                'kapasitas_kuliah.max' => 'Kapasitas kuliah maksimal 100 Orang',
            ]
            
        );

        DB::table('kuliah')
        ->where('kode_kuliah', $kode_kuliah)
        ->update([
            'nama_dosen' => $request->dosen_pengajar,
            'kapasitas_kuliah'=> $request->kapasitas_kuliah,
        ]);
        return redirect('/managekuliah')->with('status', 'Data kuliah berhasil diubah');
    }


    public function destroy($kode_kuliah)
    {
        
        DB::table('kuliah')->where('kode_kuliah', $kode_kuliah)->delete();
        return redirect('/managekuliah')->with('status', 'Data kuliah berhasil dihapus!');
    }
}
