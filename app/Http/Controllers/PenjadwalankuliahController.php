<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;



class PenjadwalankuliahController extends Controller
{
    public function generatejadwalform(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $semester = DB::table('semester')->get();

        $allTahunAjaran = DB::table('tahun_ajaran')->get();


        $countKuliahTabel = [];
        foreach($allTahunAjaran as $tahun){
            $countGanjil = DB::table('kuliah')->where('kode_semester', 1)->where('tahun_ajaran', $tahun->tahun_ajaran)->count();
            $countGenap = DB::table('kuliah')->where('kode_semester', 2)->where('tahun_ajaran', $tahun->tahun_ajaran)->count();
            array_push(
                $countKuliahTabel, [
                'tahun_ajaran' => $tahun->tahun_ajaran,
                'semester_ganjil_count' => $countGanjil,
                'semester_genap_count' => $countGenap]
             );
        }

        // dd($tahun_ajaran);

        $kuliah = DB::table('kuliah')->get();
        $ruang = DB::table('ruang')->get();
        $waktu = DB::table('waktu')->get();

        $allDosen = DB::table('dosen')->get();
        $allHari = DB::table('hari')->get();

        if(count($kuliah) == 0) {
            return redirect('/managekuliah/managekelas')->with('status', 'Harap Mengisi Data Kelas Terlebih Dahulu!');
        }

        foreach($countKuliahTabel as $countKuliah){
            if($countKuliah['semester_ganjil_count'] == 0) {
                return redirect('/managekuliah/managekelas')->with('status', 'Harap Menambahkan Data Kelas di Semester Ganjil Tahun Ajaran '.$countKuliah['tahun_ajaran']);
            }
    
            if($countKuliah['semester_genap_count'] == 0) {
                return redirect('/managekuliah/managekelas')->with('status', 'Harap Menambahkan Data Kelas di Semester Genap Tahun Ajaran '.$countKuliah['tahun_ajaran']);
            }
        }
        

        if(count($ruang) == 0) {
            return redirect('/manageruang')->with('status', 'Harap Mengisi Data Ruang Terlebih Dahulu!');
        }

        if(count($waktu) == 0) {
            return redirect('/managewaktu')->with('status', 'Harap Mengisi Data Waktu Terlebih Dahulu!');
        }

        foreach($countKuliahTabel as $countKuliah){
            $kuliah = DB::table('kuliah')->where('tahun_ajaran', $countKuliah['tahun_ajaran'])->get();
            if ($kuliah[count($kuliah)-1]->kode_kuliah != count($kuliah)) {
            // if (DB::table('kuliah')->max('kode_kuliah')->first() != count($kuliah)) {
                for ($i=0; $i < count($kuliah); $i++) { 
                    DB::table('kuliah')
                    ->where('kode_kuliah', $kuliah[$i]->kode_kuliah)
                    ->where('tahun_ajaran', $countKuliah['tahun_ajaran'])
                    ->update([
                        'kode_kuliah' => $i+1,
                    ]);
                }
            }

        }

        if ($ruang[count($ruang)-1]->kode_ruang != count($ruang)) {
            for ($i=0; $i < count($ruang); $i++) { 
                DB::table('ruang')
                ->where('kode_ruang', $ruang[$i]->kode_ruang)
                ->update([
                    'kode_ruang' => $i+1,
                ]);
            }
        }
        if ($waktu[count($waktu)-1]->kode_waktu != count($waktu)) {
            for ($i=0; $i < count($waktu); $i++) { 
                DB::table('waktu')
                ->where('kode_waktu', $waktu[$i]->kode_waktu)
                ->update([
                    'kode_waktu' => $i+1,
                ]);
            }
        }

        $algoritma_proses = [];
        $execution_time = [];


        return view('penjadwalankuliah.generatejadwal', compact('user_login','semester','algoritma_proses','countRequest','execution_time','allDosen', 'allHari', 'countKuliahTabel','allTahunAjaran'));
    }

    public function generate_action(Request $request){
        if($request->ajax()){
            if($request->has('dosen')){

                $namaDosen = $request->get('dosen');
                $kodeDosen = DB::table('dosen')->where('nama', $namaDosen)->first()->kode_dosen;
                $semester = $request->get('semester');
                $tahun_ajaran = $request->get('tahun_ajaran');
                $kodeKelasBySemesterAndYear = DB::table('kuliah')->where('kode_dosen',$kodeDosen)->where('kode_semester', $semester)->where('tahun_ajaran', $tahun_ajaran)->get();
                foreach ($kodeKelasBySemesterAndYear as $key => $kelas) {
                    $kelasBySemesterAndYear[$key] = DB::table('kelas')->where('kode_kelas', $kelas->kode_kelas)->where('tahun_ajaran', $tahun_ajaran)->first();
                }
                
                $data = array(
                    'allKelas'  => $kelasBySemesterAndYear
                );
                
                echo json_encode($data);
            }   

            if($request->has('hari')) {
                $kodeHari = $request->get('hari');

                $getKodeJamByHari = DB::table('waktu')->where('kode_hari', $kodeHari)->get();

                $allJamByKodeJam = [];

                foreach($getKodeJamByHari as $key => $jam) {
                    $allJamByKodeJam[$key] = DB::table('jam')->where('kode_jam', $jam->kode_jam)->first();
                }

                $data = array(
                    'allJam' => $allJamByKodeJam
                );

                echo json_encode($data);

            }

            if($request->has('i')) {
                $getAllDosen = DB::table('dosen')->get();
                $getAllHari = DB::table('hari')->get();

                $data = array(
                    'dosen' => $getAllDosen,
                    'hari' => $getAllHari
                );

                echo json_encode($data);
            }
        }
    }

    public function generatejadwal(Request $request)
    {

        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        $semester = DB::table('semester')->get();
        $allDosen = DB::table('dosen')->get();
        $allHari = DB::table('hari')->get();
        $allTahunAjaran = DB::table('tahun_ajaran')->get();
        $jumlahIndividu = $request->individu;
        $maxGenerasi = $request->generasi;
        $tahunAjaran = $request->tahun_ajaran;
        $kodeSemester = $request->radioSemester;
        $showAlgorithm = $request->algoritma;
        $crossoverRate = $request->crossover_rate;

        $countKuliahTabel = [];
        foreach($allTahunAjaran as $tahun){
            $countGanjil = DB::table('kuliah')->where('kode_semester', 1)->where('tahun_ajaran', $tahun->tahun_ajaran)->count();
            $countGenap = DB::table('kuliah')->where('kode_semester', 2)->where('tahun_ajaran', $tahun->tahun_ajaran)->count();
            array_push(
                $countKuliahTabel, [
                'tahun_ajaran' => $tahun->tahun_ajaran,
                'semester_ganjil_count' => $countGanjil,
                'semester_genap_count' => $countGenap]
             );
        }

        if(!$kodeSemester){
            Session::flash('errorSemester', "Harap Memilih Semester Terlebih Dahulu!");
            return Redirect::back();
        }

        if(!$tahunAjaran){ 
            Session::flash('errorTahunAjaran', "Harap Memilih Tahun Ajaran Terlebih Dahulu!");
            return Redirect::back();
        }

        if($maxGenerasi < 1){ 
            Session::flash('errorJumlahGenerasi', "Generasi Minimal 1!");
            return Redirect::back();
        }

        if($jumlahIndividu < 4){ 
            Session::flash('errorJumlahIndividu', "Individu Minimal 4!");
            return Redirect::back();
        }


        // request prioritas kelas


        $kelas = $request->kelas;

        $hari = [];
        foreach ( (array) $request->hari as $h ) {
            if($h != null) {
                array_push($hari, $h);
            }
        }
        $jam = $request->jam;

        $kode_waktu = [];
        foreach( (array) $hari as $key => $value) {
            array_push($kode_waktu,
                DB::table('waktu')->where('kode_hari', $value)->where('kode_jam', $jam[$key])->first()->kode_waktu
            );
        }


        $kode_kuliah = [];
        foreach( (array)$kelas as $key => $value ) {
            array_push($kode_kuliah,
                DB::table('kuliah')->where('kode_kelas', $value)->first()->kode_kuliah
            );
        }

        $prioritas_kelas = [];
        foreach((array)$kelas as $key => $value) {
            array_push($prioritas_kelas, [
                'kode_kuliah' => $kode_kuliah[$key],
                'kode_kelas' => $value,
                'kode_waktu' => $kode_waktu[$key]
            ]);
        }

        // ------ //

        $kuliahTable = DB::table('kuliah')->where('kode_semester',$kodeSemester)->where('tahun_ajaran', $tahunAjaran)->get();


        $ruangTable = DB::table('ruang')->get();

        $firstKodeRuang = $ruangTable[0]->kode_ruang;
        $lastKodeRuang = $ruangTable[count($ruangTable)-1]->kode_ruang;


        $waktuTable = DB::table('waktu')->get();
        $firstKodeWaktu = $waktuTable[0]->kode_waktu;
        $lastKodeWaktu = $waktuTable[count($waktuTable)-1]->kode_waktu;

        function random_kode_ruang($kode_prodi){
            $nama_prodi = DB::table('prodi')->where('kode_prodi',$kode_prodi)->first()->nama_prodi;
            $ruangByProdi = DB::table('ruang')->where('nama_prodi',$nama_prodi)->get();

            // Jika Ruangan dengan prodi yang dimaksud tidak ada
            if (count($ruangByProdi) == 0)
            {
                $ruangByProdi = DB::table('ruang')->get();
            }
            // ------

            $allKodeRuangByProdi = [];

            foreach ($ruangByProdi as $ruang) {
                array_push($allKodeRuangByProdi, $ruang->kode_ruang);
            }

            $random = $allKodeRuangByProdi[mt_rand(0, count($allKodeRuangByProdi) - 1)];

            return $random;
        }

        // function random 0-1
        function random_1($individu){
            $random = [];
            for ($i=0; $i < count($individu); $i++) { 
                $random[$i] = (rand(0,1000)/1000);
            }
            return $random;
        }

        // function random 1-panjang individu - 1
        function random_2($individu){
            $length = count($individu) - 1;
            $random = rand(1, $length);
            return $random;
        }

        // individuWithDetail function
        function individuWithDetail($individu, $tahun_ajaran){
            $individuWithDetail = [];
            for ($i = 0; $i < count($individu); $i++) {
                $individuWithDetail[$i] = [];
                for ($j=0; $j < count($individu[$i]); $j++) { 
                    $kode_matkul = DB::table('kuliah')->where('kode_kuliah',$individu[$i][$j][0])->where('tahun_ajaran', $tahun_ajaran)->first()->kode_matkul;
                    $kode_dosen = [ 
                        'kode' => DB::table('kuliah')->where('kode_kuliah',$individu[$i][$j][0])->first()->kode_dosen, 
                        'clash' => 0
                    ];
                    $kode_kelas = DB::table('kuliah')->where('kode_kuliah',$individu[$i][$j][0])->where('tahun_ajaran', $tahun_ajaran)->first()->kode_kelas;
                    $jumlah_sks = DB::table('matkul')->where('kode_matkul',$kode_matkul)->first()->sks;
                    $nama_ruang = [
                        'kode' => DB::table('ruang')->where('kode_ruang',$individu[$i][$j][1])->first()->nama_ruang, 
                        'clash' => 0
                    ];
                    $kode_hari = DB::table('waktu')->where('kode_waktu',$individu[$i][$j][2])->first()->kode_hari;
                    $kode_jam = DB::table('waktu')->where('kode_waktu',$individu[$i][$j][2])->first()->kode_jam;

                    array_push($individuWithDetail[$i],
                    [ 'kode_matkul' => $kode_matkul, 
                    'kode_dosen' => $kode_dosen, 
                    'kode_kelas' => $kode_kelas, 
                    'jumlah_sks' => $jumlah_sks, 
                    'nama_ruang' => $nama_ruang, 
                    'kode_hari' => $kode_hari, 
                    'kode_jam' => $kode_jam]);            
                }

            }

            

            $clashDosen = [];
            $clashRuang = [];

            for ($i=0; $i < count($individuWithDetail) ; $i++) { 
                for ($a=0; $a < count($individuWithDetail[$i]) ; $a++) { 
                    for ($b=0; $b < count($individuWithDetail[$i]) ; $b++) { 
                        if ($a == $b){
                            continue;
                        }

                        // Check class dosen
                        if($individuWithDetail[$i][$a]['kode_dosen']['kode'] == $individuWithDetail[$i][$b]['kode_dosen']['kode']) { 
                            if($individuWithDetail[$i][$a]['kode_hari'] == $individuWithDetail[$i][$b]['kode_hari']) {
                                if($individuWithDetail[$i][$a]['kode_jam'] > $individuWithDetail[$i][$b]['kode_jam']) {
                                    if(($individuWithDetail[$i][$b]['kode_jam'] - 1) + $individuWithDetail[$i][$b]['jumlah_sks'] >= $individuWithDetail[$i][$a]['kode_jam']) {
                                        array_push($clashDosen,"$i-$a-$b");
                                        $individuWithDetail[$i][$a]['kode_dosen']['clash'] = 1;
                                        $individuWithDetail[$i][$b]['kode_dosen']['clash'] = 1;
                                    }
                                } elseif($individuWithDetail[$i][$a]['kode_jam'] < $individuWithDetail[$i][$b]['kode_jam']) {
                                    if(($individuWithDetail[$i][$a]['kode_jam'] - 1) + $individuWithDetail[$i][$a]['jumlah_sks'] >= $individuWithDetail[$i][$b]['kode_jam']) {
                                        array_push($clashDosen,"$i-$a-$b");
                                        $individuWithDetail[$i][$a]['kode_dosen']['clash'] = 1;
                                        $individuWithDetail[$i][$b]['kode_dosen']['clash'] = 1;
                                    }
                                } else {
                                    array_push($clashDosen,"$i-$a-$b");
                                    $individuWithDetail[$i][$a]['kode_dosen']['clash'] = 1;
                                    $individuWithDetail[$i][$b]['kode_dosen']['clash'] = 1;
                                }
                            }
                        }

                        // Check class ruang
                        if($individuWithDetail[$i][$a]['nama_ruang']['kode'] == $individuWithDetail[$i][$b]['nama_ruang']['kode']) { 
                            if($individuWithDetail[$i][$a]['kode_hari'] == $individuWithDetail[$i][$b]['kode_hari']) {
                                if($individuWithDetail[$i][$a]['kode_jam'] > $individuWithDetail[$i][$b]['kode_jam']) {
                                    if(($individuWithDetail[$i][$b]['kode_jam'] - 1) + $individuWithDetail[$i][$b]['jumlah_sks'] >= $individuWithDetail[$i][$a]['kode_jam']) {
                                        array_push($clashRuang,"$i-$a-$b");
                                        $individuWithDetail[$i][$a]['nama_ruang']['clash'] = 1;
                                        $individuWithDetail[$i][$b]['nama_ruang']['clash'] = 1;
                                    }
                                } elseif($individuWithDetail[$i][$a]['kode_jam'] < $individuWithDetail[$i][$b]['kode_jam']) {
                                    if(($individuWithDetail[$i][$a]['kode_jam'] - 1) + $individuWithDetail[$i][$a]['jumlah_sks'] >= $individuWithDetail[$i][$b]['kode_jam']) {
                                        array_push($clashRuang,"$i-$a-$b");
                                        $individuWithDetail[$i][$a]['nama_ruang']['clash'] = 1;
                                        $individuWithDetail[$i][$b]['nama_ruang']['clash'] = 1;
                                    }
                                } else {
                                        array_push($clashRuang,"$i-$a-$b");
                                        $individuWithDetail[$i][$a]['nama_ruang']['clash'] = 1;
                                        $individuWithDetail[$i][$b]['nama_ruang']['clash'] = 1;
                                }
                            }
                        }
                    }
                }
            }

            return $individuWithDetail;
        }

        // change code into name in individuDetail, codeIntoNameIndividuDetail function 
        function codeIntoNameIndividuDetail($individuWithDetail, $tahun_ajaran){
            $codeIntoNameIndividuDetail = [];

            for ($i=0; $i<count($individuWithDetail); $i++) {
                for ($j=0; $j<count($individuWithDetail[$i]); $j++) {

                    // Change Code into name in table
                    $codeIntoNameIndividuDetail[$i][$j] = [
                        'kode_kelas' => $individuWithDetail[$i][$j]['kode_kelas'],
                        'matkul' => DB::table('matkul')->where('kode_matkul',$individuWithDetail[$i][$j]['kode_matkul'])->where('tahun_ajaran', $tahun_ajaran)->first()->nama_matkul,
                        'dosen' => DB::table('dosen')->where('kode_dosen',$individuWithDetail[$i][$j]['kode_dosen']['kode'])->first()->nama,
                        'kelas' => DB::table('kelas')->where('kode_kelas',$individuWithDetail[$i][$j]['kode_kelas'])->where('tahun_ajaran', $tahun_ajaran)->first()->kelas,
                        'jumlah_sks' => $individuWithDetail[$i][$j]['jumlah_sks'],
                        'nama_ruang' => $individuWithDetail[$i][$j]['nama_ruang']['kode'],
                        'hari' => DB::table('hari')->where('kode_hari',$individuWithDetail[$i][$j]['kode_hari'])->first()->nama_hari,
                        'jam' => DB::table('jam')->where('kode_jam',$individuWithDetail[$i][$j]['kode_jam'])->first()->jam,
                    ];
                }
            }

            return $codeIntoNameIndividuDetail;
        }

        // fitness function
        function fitness($individuWithDetail)
        {
            $fitness_function = [];

            for ($i=0; $i < count($individuWithDetail); $i++) { 
                $CD[$i] = 0;
                $CR[$i] = 0;
                for ($j=0; $j < count($individuWithDetail[$i]); $j++) { 
    
                    if($individuWithDetail[$i][$j]['kode_dosen']['clash'] == 1){
                        $CD[$i]++;
                    }
    
                    if($individuWithDetail[$i][$j]['nama_ruang']['clash'] == 1){
                        $CR[$i]++;
                    }
                }
    
                $CD[$i] = (int)ceil($CD[$i] / 2);
                $CR[$i] = (int)ceil($CR[$i] / 2);
            }
    
            $fitness_function["CD"] = $CD;
            $fitness_function["CR"] = $CR;
    
            $fitnessIndividu = [];
            $total_nilai_fitness = 0;
    
            for ($i=0; $i < count($individuWithDetail); $i++) { 
                $fitnessIndividu[$i] = 1 / ( 1 + ($CD[$i] + $CR[$i]) );
                $total_nilai_fitness += $fitnessIndividu[$i];
            }

            $fitness_function["fitness_individu"] = $fitnessIndividu;
            $fitness_function["total_fitness"] = $total_nilai_fitness;
    
            $hasOne = array_keys($fitnessIndividu, 1);
    
            $fixJadwal = [];
            
            if ($hasOne) {
                for ($i=0; $i < count($hasOne); $i++) { 
                    $fixJadwal[$i] = $individuWithDetail[$hasOne[$i]];
                }
            }
            
            $fitness_function["fix_jadwal"] = $fixJadwal;

            return $fitness_function;
        }

        // allClashChromo Function
        function allClashChromosome($individu, $tahun_ajaran){
            $individuWithDetail = individuWithDetail($individu, $tahun_ajaran);
            $allClashChromosome = [];

            // looping individu
            for ($i=0; $i < count($individuWithDetail); $i++) { 

                // looping kromosom tiap individu
                for ($j=0; $j < count($individuWithDetail[$i]); $j++) { 
                    if($individuWithDetail[$i][$j]["kode_dosen"]["clash"] == 1 || $individuWithDetail[$i][$j]["nama_ruang"]["clash"] == 1) {
                        array_push($allClashChromosome, [
                            "kromosom" => $individu[$i][$j],
                            "index_individu" => $i,
                            "index_kromosom" => $j
                        ]);
                    }
                }
            }

            return $allClashChromosome;
            
        }

        // 1. Inisialisasi Populasi. 


        $individu = [];
        for ($i=0; $i < $jumlahIndividu; $i++) { 
            $individu[$i] = [];

            foreach ($kuliahTable as $kuliah) {


                if (count($prioritas_kelas) != 0) {
                    $tea = 0;
                    for($j=0; $j < count($prioritas_kelas); $j++) {
                        if($kuliah->kode_kelas == $prioritas_kelas[$j]['kode_kelas']){
                            $tea = $prioritas_kelas[$j];
                        }

                        if($j == count($prioritas_kelas) - 1) {
                            if ($tea != 0) {
                                array_push($individu[$i],[
                                $kuliah->kode_kuliah, 
                                random_kode_ruang($kuliah->kode_prodi),
                                $tea['kode_waktu']
                                ]);
                            } else {
                                array_push($individu[$i],[
                                    $kuliah->kode_kuliah, 
                                    random_kode_ruang($kuliah->kode_prodi),
                                    rand($firstKodeWaktu,$lastKodeWaktu)
                                ]);
                            }
                        }
                        
                    }
                } else {
                    array_push($individu[$i],[
                        $kuliah->kode_kuliah, 
                        random_kode_ruang($kuliah->kode_prodi),
                        rand($firstKodeWaktu,$lastKodeWaktu)
                    ]);
                }
            }
        }



        /* === ALGORITMA START === */

        $algoritma_proses = [];
        $time_start = microtime(true); 
        $fixJadwal = [];
        $generasi = 0;

        while($generasi < $maxGenerasi && count($fixJadwal) == 0){

            
            // echo('==== GENERASI KE-'. ($generasi+1) .' =======');    
            
            $individuWithDetail = individuWithDetail($individu, $tahunAjaran);

            
            $algoritma_proses[$generasi]["individu"] = $individu;
            $algoritma_proses[$generasi]["individuWithDetail"] = $individuWithDetail;
            $algoritma_proses[$generasi]["individuWithDetail_with_name"] = codeIntoNameIndividuDetail($individuWithDetail, $tahunAjaran);

            // 2. Fitness Function

            $fitness_function = fitness($individuWithDetail);
            $CD = $fitness_function['CD'];
            $CR = $fitness_function['CR'];
            $fitnessIndividu = $fitness_function['fitness_individu'];
            $total_nilai_fitness = $fitness_function['total_fitness'];
            $fixJadwal = $fitness_function['fix_jadwal'];

            $algoritma_proses[$generasi]["CD"] = $CD;
            $algoritma_proses[$generasi]["CR"] = $CR;
            $algoritma_proses[$generasi]["fitness_individu"] = $fitnessIndividu;
            $algoritma_proses[$generasi]["total_fitness"] = $total_nilai_fitness;
            $algoritma_proses[$generasi]["fix_jadwal"] = $fixJadwal;

            if ($fixJadwal) break;
            
            // SELECTION (roullate Wheel)
            
            // 1.hitung probabilitas

            $probabilitas = [];
            for ($i=0; $i < count($fitnessIndividu); $i++) { 
                $probabilitas[$i] = $fitnessIndividu[$i]/$total_nilai_fitness;
            }

            $algoritma_proses[$generasi]["probabilitas"] = $probabilitas;


            // 2.hitung kumulatif
            
            $kumulatif = [];
            $total_kumulatif = 0;
            for ($i=0; $i < count($probabilitas); $i++) { 
                $kumulatif[$i] = $probabilitas[$i] + $total_kumulatif;
                $total_kumulatif = $kumulatif[$i];
            }

            $algoritma_proses[$generasi]["kumulatif"] = $kumulatif;
            $algoritma_proses[$generasi]["total_kumulatif"] = $total_kumulatif;
            
            // 3. Bangkitkan bilangan acak 0-1

            $random = random_1($individu);

            $algoritma_proses[$generasi]["random1_selection"] = $random;
            
            // 4. seleksi new individu 
            
            $newIndividu = [];
            $listNewIndividu = [];
            for ($i=0; $i < count($individu); $i++) { 
                for ($j=0; $j < count($random); $j++) { 
                    $newIndividu[$i] = $random[$i] <= $kumulatif[$j] ? $individu[$j]:[];
                    if($newIndividu[$i]) {
                        array_push($listNewIndividu,$j);
                        break;
                    }
                }
            }

            $algoritma_proses[$generasi]["list_new_individu_selection"] = $listNewIndividu;
            $algoritma_proses[$generasi]["new_individu_selection"] = $newIndividu;

            // CROSSOVER

            

            // 2. individu terpilih sebagai parent dan posisi one cut point
            
            $PC = $crossoverRate / 100;
            $indexIndividuSelected = [];

            $algoritma_proses[$generasi]["PC"] = $PC;


            while(count($indexIndividuSelected) < 3){

                // 1. Bangkitkan bilangan acak 0-1
                $random = random_1($individu);
                // echo"Random 0-1 //0.123";
                // dump($random);

                for ($i=0; $i < count($random); $i++) { 
                    if ($random[$i] < $PC) {
                        array_push($indexIndividuSelected,$i);
                    }
                }

                if(count($indexIndividuSelected) < 3){
                    $indexIndividuSelected = [];
                }
            }

            $algoritma_proses[$generasi]["random1_crossover"] = $random;
            $algoritma_proses[$generasi]["index_best_individu"] = $indexIndividuSelected;

            $parents = [];
            for ($i=0; $i < count($indexIndividuSelected); $i++) { 
                $parents[$i] = [];
                $lastIndex = count($indexIndividuSelected) - 1;

                
                    $father = $indexIndividuSelected[$i];
                    if ($i == $lastIndex){
                        $mother = $indexIndividuSelected[0];
                    } else {
                        $mother = $indexIndividuSelected[$i+1];
                    }
                    $parents[$i] = [
                        'father' => $father,
                        'mother' => $mother,
                        'cut-point' => random_2($individu[0]),
                    ];
                
                
            }

            $algoritma_proses[$generasi]["parents"] = $parents;

            // 3. offspring hasil kawin silang parent
            
            $offSpring = [];
            for ($i=0; $i < count($parents); $i++) { 
                $offSpring[$i] = [];
                $first_kromosom = [];
                $last_kromosom = [];

                array_push($first_kromosom, array_chunk($newIndividu[$parents[$i]['father']], $parents[$i]['cut-point'] )[0] );

                $new_cut_point = count($newIndividu[$parents[$i]['father']]) - $parents[$i]['cut-point'];

                array_push($last_kromosom, array_reverse(array_chunk(array_reverse($newIndividu[$parents[$i]['mother']]), $new_cut_point )[0]));

                array_push($offSpring[$i], array_merge($first_kromosom[0],$last_kromosom[0]) );

                $offSpring[$i] = $offSpring[$i][0];

            }

            $algoritma_proses[$generasi]["offSpring"] = $offSpring;

            // echo"off spring";

            
            for ($i=0; $i < count($indexIndividuSelected); $i++) { 
                $newIndividu[$indexIndividuSelected[$i]] = $offSpring[$i];
            }

            $individuWithDetail = individuWithDetail($newIndividu, $tahunAjaran);


            $algoritma_proses[$generasi]["new_individu_crossover"] = $newIndividu;
            $algoritma_proses[$generasi]["new_individu_crossover_with_detail"] = $individuWithDetail;

            // Fitness Function

            $fitness_function = fitness($individuWithDetail);
            $CD = $fitness_function['CD'];
            $CR = $fitness_function['CR'];
            $fitnessIndividu = $fitness_function['fitness_individu'];
            $total_nilai_fitness = $fitness_function['total_fitness'];
            $fixJadwal = $fitness_function['fix_jadwal'];

            $algoritma_proses[$generasi]["new_CD"] = $CD;
            $algoritma_proses[$generasi]["new_CR"] = $CR;
            $algoritma_proses[$generasi]["new_fitness_individu"] = $fitnessIndividu;
            $algoritma_proses[$generasi]["new_total_fitness"] = $total_nilai_fitness;
            $algoritma_proses[$generasi]["new_fix_jadwal"] = $fixJadwal;

            if ($fixJadwal) break;

            // MUTASI

            $allClashChromosome = allClashChromosome($newIndividu,$tahunAjaran);
            $algoritma_proses[$generasi]["all_clash_chromosome"] = $allClashChromosome;


            for ($i=0; $i < count($allClashChromosome); $i++) { 
                $nama_prodi = DB::table('ruang')->where('kode_ruang',$allClashChromosome[$i]['kromosom'][1])->first()->nama_prodi;
                $kode_prodi = DB::table('prodi')->where('nama_prodi',$nama_prodi)->first()->kode_prodi;
                // $kode_prodi = 'TIF';

                if (count($prioritas_kelas) != 0) {
                    $tea = 0;
                    for($j=0; $j < count($prioritas_kelas); $j++) {
                        if($allClashChromosome[$i]['kromosom'][0] == $prioritas_kelas[$j]['kode_kuliah']){
                            $tea = $prioritas_kelas[$j];
                        }

                        if($j == count($prioritas_kelas) - 1) {
                            if ($tea != 0) {
                                $mutatedChro =[
                                $allClashChromosome[$i]['kromosom'][0],
                                random_kode_ruang($kode_prodi),
                                $tea['kode_waktu']
                            ];
                            } else {
                                $mutatedChro =[
                                $allClashChromosome[$i]['kromosom'][0],
                                random_kode_ruang($kode_prodi),
                                rand($firstKodeWaktu,$lastKodeWaktu)
                                ];
                            }
                        }
                    }
                } else {
                    $mutatedChro =[
                        $allClashChromosome[$i]['kromosom'][0],
                        random_kode_ruang($kode_prodi),
                        rand($firstKodeWaktu,$lastKodeWaktu)
                    ];
                }


                $algoritma_proses[$generasi]["mutated_chromosome"][$i] = $mutatedChro;

                $newIndividu[$allClashChromosome[$i]['index_individu']][$allClashChromosome[$i]['index_kromosom']] = $mutatedChro;
            }
            
            
            
            $algoritma_proses[$generasi]["new_individu_has_mutated"] = $newIndividu;

            $individu = $newIndividu;

            $generasi++;

        }
        // echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);

        $execution_time = microtime(true) - $time_start;

        // echo "ALGORITMA PROSESS";
        if(!$showAlgorithm) {
            $algoritma_proses = [];
        }

        return view('penjadwalankuliah.generatejadwal', compact('user_login','semester','algoritma_proses','execution_time','fixJadwal','kodeSemester','countRequest','tahunAjaran', 'allTahunAjaran', 'allDosen','allHari', 'countKuliahTabel'));
    }
public function hasilgenerate(Request $request, $jadwal_index)
{
    $allJadwal = $request->session()->get('jadwal');
    $kode_semester = $request->session()->get('kodeSemester');
    $tahun_ajaran = $request->session()->get('tahunAjaran');

    $nama_semester = DB::table('semester')->where('kode_semester', $kode_semester)->first()->nama_semester;
    $jadwalTable = DB::table('jadwal')->where('semester', $nama_semester)->get();

    $fixJadwal = $allJadwal[$jadwal_index];

    if (count($jadwalTable) > 0) {
        DB::table('jadwal')->where('semester', $nama_semester)->where('tahun_ajaran', $tahun_ajaran)->delete();
    }

    // Sort fixJadwal first by kelas, then by kode_hari (to sort by day), and then by kode_jam (time)
   $daysOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat','sabtu','minggu'];

usort($fixJadwal, function ($a, $b) use ($daysOrder) {
    $kelasA = DB::table('kelas')->where('kode_kelas', $a['kode_kelas'])->first()->kelas;
    $kelasB = DB::table('kelas')->where('kode_kelas', $b['kode_kelas'])->first()->kelas;

    if ($kelasA === $kelasB) {
        $hariA = DB::table('hari')->where('kode_hari', $a['kode_hari'])->first()->nama_hari;
        $hariB = DB::table('hari')->where('kode_hari', $b['kode_hari'])->first()->nama_hari;

        // Urutkan hari berdasarkan array $daysOrder
        $dayIndexA = array_search(strtolower($hariA), $daysOrder);
        $dayIndexB = array_search(strtolower($hariB), $daysOrder);

        if ($dayIndexA === $dayIndexB) {
            return strcmp($a['kode_jam'], $b['kode_jam']);
        }
        return $dayIndexA <=> $dayIndexB;
    }
    return strcmp($kelasA, $kelasB);
});
    // INSERT DATA KE TABEL JADWAL KULIAH
    foreach ($fixJadwal as $row) {
        // Calculate jam_keluar as before
        $menit_dalam_sks = $row['jumlah_sks'] * 45;
        $jam_masuk = DB::table('jam')->where('kode_jam', $row['kode_jam'])->first()->jam;
        $explode_jam = explode(':', $jam_masuk);
        $menit_dalam_jam_masuk = $explode_jam[0] * 60 + $explode_jam[1];
        $total_menit_digabungkan = $menit_dalam_jam_masuk + $menit_dalam_sks;
        $jam = floor($total_menit_digabungkan / 60);
        $menit = $total_menit_digabungkan % 60;
        if ($menit == "0") $menit = "00";
        $jam_keluar = $jam . ":" . $menit;

        // Insert into jadwal table
        DB::table('jadwal')->insert([
            'matkul' => DB::table('matkul')->where('kode_matkul', $row['kode_matkul'])->first()->nama_matkul,
            'dosen' => DB::table('dosen')->where('kode_dosen', $row['kode_dosen']['kode'])->first()->nama,
            'kelas' => DB::table('kelas')->where('kode_kelas', $row['kode_kelas'])->first()->kelas,
            'jumlah_sks' => $row['jumlah_sks'],
            'nama_ruang' => $row['nama_ruang']['kode'],
            'hari' => DB::table('hari')->where('kode_hari', $row['kode_hari'])->first()->nama_hari,
            'jam_masuk' => $jam_masuk,
            'jam_keluar' => $jam_keluar,
            'semester' => $nama_semester,
            'tahun_ajaran' => $tahun_ajaran
        ]);

        // Insert tahun ajaran if not exists
        $tahun_ajaran_exist = DB::table('tahun_ajaran')->where('tahun_ajaran', $tahun_ajaran)->first();
        if (!$tahun_ajaran_exist) {
            DB::table('tahun_ajaran')->insert([
                'tahun_ajaran' => $tahun_ajaran
            ]);
        }
    }

    return redirect('/hasiljadwal');
}


public function hasiljadwal(Request $request)
{
    $user_login = $request->session()->get('user_login');
    $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

    // Get schedule for each semester and sort by kelas, hari, and jam_masuk
    $semester = DB::table('semester')->get();
    $jadwal = [];

    for ($i = 0; $i < count($semester); $i++) {
        $jadwal[$i] = DB::table('jadwal')
            ->where('semester', $semester[$i]->nama_semester)
            ->orderBy('kelas')
            ->orderBy('hari')
            ->orderBy('jam_masuk')
            ->get();
    }

    // Get list of tahun ajaran
    $tahun_ajaran = DB::table('tahun_ajaran')->get();

    return view('penjadwalankuliah.hasiljadwal', compact('user_login', 'jadwal', 'countRequest', 'semester', 'tahun_ajaran'));
}



}

