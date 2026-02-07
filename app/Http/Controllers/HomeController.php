<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalExport;

class HomeController extends Controller
{
    public function index(Request $request) {
        $countDosen = DB::table('dosen')->count();
        $countMatkul = DB::table('matkul')->count();
        $countRuang = DB::table('ruang')->count();
        $countKelas = DB::table('kelas')->count();
        $countJadwal = DB::table('jadwal')->count();

        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        // Ambil jadwal per semester
        $semester = DB::table('semester')->get();
        for ($i=0; $i < count($semester); $i++) { 
            $jadwal[$i] = DB::table('jadwal')->where('semester', $semester[$i]->nama_semester)->get();
        }

        // list tahun ajaran yang ada
        $tahun_ajaran = DB::table('tahun_ajaran')->get();
        

        $user_login = $request->session()->get('user_login');


        return view('home',compact('user_login', 'countDosen', 'countMatkul', 'countRuang', 'countKelas', 'countJadwal', 'countRequest', 'jadwal','semester','tahun_ajaran'));
    }
    public function tampilkan_jadwal(Request $request) {

        if($request->ajax()) {

            $tahun = $request->get('tahun');
            
            $jadwal_ganjil = DB::table('jadwal')->where('tahun_ajaran', $tahun)->where('semester', 'ganjil')->get();
            $jadwal_genap = DB::table('jadwal')->where('tahun_ajaran', $tahun)->where('semester', 'genap')->get();
            
            $data = array(
                'ganjil'  => $jadwal_ganjil,
                'genap'  => $jadwal_genap,
                'tahun' => $tahun
            );
            echo json_encode($data);

        } else {
            return redirect('/home/dashboard');
        }
    }
    public function export_excel($semester, $tahun){
        return Excel::download(new JadwalExport($semester, $tahun), "jadwalkuliah{$semester}{$tahun}.xlsx");
    }
}
