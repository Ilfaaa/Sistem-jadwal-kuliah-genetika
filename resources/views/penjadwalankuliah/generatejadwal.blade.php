@extends('layouts.app')

@section('title','Generate Jadwal | Sistem Penjadwalan Kuliah')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-9">
            <h1 class="m-0">Generate Jadwal Perkuliahan Menggunakan <span class="text-maroon generate-detail" data-toggle="modal" data-target="#algoritmagenetikaDetail">Algoritma Genetika</span></h1>
          </div><!-- /.col -->
          <div class="col-sm-3">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item active">Generate Jadwal</li>
              
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                @if (session('status'))
                <div class="alert alert-dismissible fade show bg-lime" role="alert">
                    {{ session('status')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card text-choThem">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Form Generate Jadwal Perkuliahan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <form method="post" action="/generatejadwal">
                            @csrf
                            <div class="form-group">
                                <label>Jumlah <span class="text-maroon generate-detail" data-toggle="modal" data-target="#individuDetail">Individu</span> Dibangkitkan</label>
                                  <select name="individu" class="form-control select2bs4 @error('individu') is-invalid @enderror">
                                    @foreach(range('4','50') as $v):
                                    <option value="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                  </select>
                                  @if (Session::has('errorJumlahIndividu'))
                                  <p class="error-msg">{{ Session::get('errorJumlahIndividu') }}</p>
                                  @endif
                            </div>
                            <div class="form-group">
                                <label>Maksimal <span class="text-maroon generate-detail" data-toggle="modal" data-target="#generasiDetail">Generasi</span></label>
                                  <select name="generasi" class="form-control select2bs4 @error('generasi') is-invalid @enderror">
                                    @foreach(range('10','500') as $v):
                                    <option value="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                  </select>
                                  @if (Session::has('errorJumlahGenerasi'))
                                  <p class="error-msg">{{ Session::get('errorJumlahGenerasi') }}</p>
                                  @endif
                            </div>
                            <div class="form-group">
                              <label>Tahun Ajaran</label>
                                <select name="tahun_ajaran" id="the_tahun_ajaran" class="form-control select2bs4 @error('tahun_ajaran') is-invalid @enderror">
                                  <option value="" id="the_tahun_ajaran_default">-- Silahkan Pilih Tahun Ajaran --</option>
                                  @foreach($allTahunAjaran as $tahun)
                                  <option value="{{ $tahun->tahun_ajaran }}">{{ $tahun->tahun_ajaran }}</option>
                                  @endforeach
                                </select>
                                @if (Session::has('errorTahunAjaran'))
                                <p class="error-msg">{{ Session::get('errorTahunAjaran') }}</p>
                                @endif
                          </div>
                            <div class="form-group clearfix">
                                @foreach($semester as $s)
                                <div class="icheck-greenTheme">
                                  <input type="radio" id="radio{{ $s->nama_semester }}" name="radioSemester" value="{{ $s->kode_semester }}">
                                  <label for="radio{{ $s->nama_semester }}">
                                    Semester {{ ucwords($s->nama_semester) }}
                                  </label>
                                </div>
                                @endforeach
                                @if (Session::has('errorSemester'))
                                <p class="error-msg">{{ Session::get('errorSemester') }}</p>
                                @endif
                            </div>

                            <div class="form-group d-inline">
                              <label class="switch">
                              <input type="checkbox" name="algoritma" id="algoritma">
                              <span class="slider"></span>
                              </label>
                              <label class="ml-1" for="algoritma" style="cursor: pointer">Tampilkan Proses Algoritma</label>
                            </div>        
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <!-- <a href="/" class="btn btn-outline-greenTheme opsiLainBtn"><i class="fas fa-arrow-circle-right mr-1"></i>Opsi Lain</a> -->
                            <button type="submit" class="btn btn-greenTheme float-right genBtn"><i class="fas fa-dna mr-2"></i>Generate Jadwal</button>
                            <div class="row mt-4 optional-input">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label><span class="text-maroon generate-detail" data-toggle="modal" data-target="#crossOverDetail">Crossover Rate</span></label>
                                    <select name="crossover_rate" class="form-control select2bs4 @error('crossover_rate') is-invalid @enderror">
                                      @foreach(range('1','100') as $v):
                                      <option value="{{ $v }}" @if($v == 75) selected @endif>{{ $v }}</option>
                                      @endforeach
                                    </select>
                                  @error('crossover_rate')
                                  <div class="invalid-feedback">
                                      {{$message}}
                                  </div>
                                  @enderror
                                </div>

                                {{-- Request dosen --}}
                                <div class="dosen-request-container">

                                  @php
                                      
                                  $totalKuliahTabel = 0;
                                  foreach($countKuliahTabel as $countKuliah) :
                                    $totalKuliahTabel += $countKuliah['semester_ganjil_count'] + $countKuliah['semester_genap_count'];
                                  endforeach;

                                  @endphp

                                  <input type="hidden" value="{{$totalKuliahTabel}}" id='maxKelas'>

                                  @for($i=1; $i<=($totalKuliahTabel); $i++)

                                  <div class="dosen-request-wrap-{{$i}} d-none">

                                  <hr class="hr-text" data-content="Prioritas Dosen {{$i}}">
                                  <div class="form-group">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <label>Dosen Pengajar</label>
                                        <select name="dosen[]" id="select-dosen_{{$i}}"  class="form-control select2bs4 ">
                                            <option value="" id="default-select-dosen_{{$i}}" selected>-- Silahkan Pilih Dosen --</option>
                                            @foreach($allDosen as $dosen)
                                            <option value="{{ $dosen->nama }}">{{ ucwords($dosen->nama) }}</option>
                                            @endforeach

                                        </select>
                                    </div> 
                                    <div class="col-md-6">
                                        <label>Kelas</label>
                                            <select name="kelas[]" id="select-kelas_{{$i}}" disabled="disabled" class="form-control select2bs4 ">
                                              <option value="" selected id="default-select-kelas_{{$i}}">-- Kelas Yang Diajar --</option>
                                            </select>
                                            
                                    </div>
                                </div>
                                <div class="row mt-2">
                                  <div class="col-md-6">
                                    <label>Hari Mengajar</label>
                                    <select name="hari[]" id="select-hari_{{$i}}" class="form-control select2bs4">
                                      <option value="" id="default-select-hari_{{$i}}">-- Hari Mengajar --</option>
                                          <option value="" id="default-select2-hari_{{$i}}">-- Pilih Hari --</option>
                                          @foreach($allHari as $hari)
                                          <option value="{{ $hari->kode_hari }}">{{ ucwords($hari->nama_hari) }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-md-6">
                                      <label>Jam Mengajar</label>
                                          <select name="jam[]" id="select-jam_{{$i}}" disabled="disabled" class="form-control select2bs4">
                                              <option value="" selected id="default-select-jam_{{$i}}">-- Jam Mengajar --</option>
                                          </select>
                                  </div>
                              </div>
                              </div>
                            </div>

                              @endfor

                            </div>
                              {{-- End Request Dosen --}}
                              </div>
                              <div class="btn btn-greenTheme button-add mr-auto ml-auto mt-2 mb-2"><i class="fas fa-plus"></i> Tambah Prioritas Dosen</div>
                            </div>
                            

                        </div>
                    </form>
                    <!-- /.card -->
                </div>
            </div>
        </div>

        @if($algoritma_proses)
          @for ($i = 0; $i < count($algoritma_proses); $i++)
            <div class="container bg-choTheme py-4">
              <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                  <h1 class="text-center text-whiteTheme">GENERASI KE-{{ $i + 1 }}</h1>
                </div>
              </div>
              <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                  <p class="text-whiteTheme">PROSES 1 : INISIALISASI POPULASI</p>
                </div>
              </div>
              <div class="row justify-content-center align-items-center">
                @foreach($algoritma_proses[$i]['individuWithDetail'] as $individu)
                <?php $individuIndex = $loop->index ?>
                
                <div class="col-md-6">
                  <table class="table table-bordered table-hover text-center bg-light inisialisasiTable">
                    <thead>
                      <tr>
                      <th scope="col" colspan="8">Individu {{ $loop->iteration }}</th>
                      </tr>
                      <tr  class="bg-greenTheme text-whiteTheme">
                        <th scope="col" class="verticalTableHeader" rowspan="2"><p class="p-krom">Kromosom</p></th>
                        <th scope="col" colspan="3">Gen 1</th>
                        <th scope="col">Gen 2</th>
                        <th scope="col" colspan="2">Gen 3</th>
                        <th scope="col" rowspan="2" class="verticalTableHeader"><p class="p-detail">Detail</p></th>
                      </tr>
                      <tr>
                        <th scope="col">matkul</th>
                        <th scope="col">Dosen</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Ruang</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Jam</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($individu as $kromosom)
                      <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $kromosom['kode_matkul'] }}</td>
                        <td @if($kromosom['kode_dosen']['clash'] == 1) class="bg-maroon text-whiteTheme" @endif>{{ $kromosom['kode_dosen']['kode'] }}</td>
                        <td>{{ $kromosom['kode_kelas'] }}</td>
                        <td @if($kromosom['nama_ruang']['clash'] == 1) class="bg-maroon text-whiteTheme" @endif>{{ ucwords($kromosom['nama_ruang']['kode']) }}</td>
                        <td>{{ $kromosom['kode_hari'] }}</td>
                        <td>{{ $kromosom['kode_jam'] }}</td>
                        <td><i class="fas fa-info-circle text-detail" data-toggle="modal" data-target="#detail-{{ $i }}-{{ $individuIndex }}-{{ $kromosom['kode_kelas'] }}"></i></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @endforeach

              </div>
              <div class="row text-whiteTheme">
                <div class="col-md-12">
                  <p> Individu Dalam Bentuk Kode.</p>

                  @for($j=0; $j<count($algoritma_proses[$i]["individu"]); $j++)
                    <?php
                      $stringIndividu = "";
                      foreach ($algoritma_proses[$i]["individu"][$j] as $kromosom) {
                        $stringIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                      }
                      $stringIndividu = '{'.substr($stringIndividu, 0, -1).'}';
                    ?>
                    <p>{{ $j+1 }}. Individu[{{ $j+1 }}] = {{ $stringIndividu }}.</p>
                  @endfor
                </div>
              </div>
              
              <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                  <p class="text-whiteTheme">PROSES 2 : MENGHITUNG FITNESS FUNCTION</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="text-whiteTheme">
                  <tr>
                    <td rowspan="2" class="pr-2">Fitness Function = </td>
                    <td style="border-bottom:solid 1px #FDE8CD;" class="text-center">1</td>
                  </tr>
                  <tr>
                    <td class="text-center">1+( CD+CR )</td>
                  </tr>
                  @foreach($algoritma_proses[$i]['fitness_individu'] as $fitness_individu)
                    <tr>
                      <td rowspan="2" class="pr-2">Fitness Individu {{ $loop->iteration }}  : </td>
                      <td style="border-bottom:solid 1px #FDE8CD;" class="text-center">1</td>
                      <td rowspan="2" class="pl-2"> = {{ round($fitness_individu,2) }}</td>
                    </tr>
                    <tr>
                      <td class="text-center">1+( {{ $algoritma_proses[$i]['CD'][$loop->index] }} + {{ $algoritma_proses[$i]['CR'][$loop->index] }})</td>
                    </tr>
                  @endforeach
                  </table>
                    <p class="mt-1 text-whiteTheme">Total Nilai Fitness = {{ round($algoritma_proses[$i]['total_fitness'], 2) }}</p>
                </div>
              </div>

              @if(array_key_exists("probabilitas",$algoritma_proses[$i]))
              <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                  <p class="text-whiteTheme">PROSES 3 : SELECTION (METODE ROULETTE WHEEL)</p>
                </div>
              </div>
              <div class="row text-whiteTheme">
                <div class="col-md-12">
                  <p>Probability = fitness[i] / total fitness</p>
                  <p> 1. Hitung Probabilitas</p>
                  <div class="pl-2">
                    @foreach($algoritma_proses[$i]['fitness_individu'] as $individu)
                      <p>{{ $loop->iteration }}. Probabilitas[{{ $loop->iteration }}] = {{ round($individu,2) }} / {{ round($algoritma_proses[$i]['total_fitness'],2) }} = {{ round($algoritma_proses[$i]['probabilitas'][$loop->index], 2) }}.</p>
                    @endforeach
                  </div>
                  <p> 2. Hitung Kumulatif</p>
                  <div class="pl-2">
                    @for($j = 0; $j<count($algoritma_proses[$i]['kumulatif']); $j++)
                      @if($j == 0)
                      <p>{{ $j+1 }}. Kumulatif[{{ $j+1 }}] = {{ round($algoritma_proses[$i]['probabilitas'][$j],2) }}.</p>
                      @else
                      <p>{{ $j+1 }}. Kumulatif[{{ $j+1 }}] = {{ round($algoritma_proses[$i]['kumulatif']
                        [$j-1],2) }} + {{ round($algoritma_proses[$i]['probabilitas'][$j],2) }} = {{ round($algoritma_proses[$i]['kumulatif'][$j], 2) }}.</p>
                      @endif
                    @endfor
                  </div>
                  <p> 3. Bangkitkan Bilangan Acak 1-0</p>
                  <div class="pl-2">
                    @for($j = 0; $j < count($algoritma_proses[$i]["random1_selection"]); $j++)
                    <p>{{ $j+1 }}. Random[{{ $j+1 }}] = {{ $algoritma_proses[$i]['random1_selection'][$j] }}.</p>
                    @endfor
                  </div>
                  <p> 4. Menggantikan Individu Lama berdasarkan nilai acak terhadap nilai kumulatif. </p>
                  {{-- $algoritma_proses[$generasi]["list_new_individu_selection"] --}}
                  <div class="pl-2">
                    @for($j = 0; $j < count($algoritma_proses[$i]["individu"]); $j++)
                    <p>{{ $j+1 }}. Individu[{{ $j+1 }}] = Individu[{{ $algoritma_proses[$i]["list_new_individu_selection"][$j] +1 }}].</p>
                    @endfor
                  </div>
                  <p> 5. Hasil Seleksi Individu Baru. </p>

                    @for($j = 0; $j < count($algoritma_proses[$i]["list_new_individu_selection"]); $j++)
                    
                    <?php
                      $stringNewIndividu = "";
                      foreach ($algoritma_proses[$i]["individu"][$algoritma_proses[$i]["list_new_individu_selection"][$j]] as $kromosom) {
                        $stringNewIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                      }
                      $stringNewIndividu = '{'.substr($stringNewIndividu, 0, -1).'}';
                    ?>

                    <p>{{ $j+1 }}. Individu[{{ $j+1 }}] = {{ $stringNewIndividu }}.</p>
                    @endfor
                </div>
              </div>
              @endif

              @if(array_key_exists("index_best_individu",$algoritma_proses[$i]))
              <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                  <p class="text-whiteTheme">PROSES 4 : CROSSOVER (METODE One Cut-Point CROSSOVER)</p>
                </div>
              </div>
              <div class="row text-whiteTheme">
                <div class="col-md-12">
                  <p>CrossOver Rate (PC) = {{ $algoritma_proses[$i]["PC"] }}</p>
                  <p> 1. Bangkitkan Bilangan Acak 1-0</p>
                  <div class="pl-2">
                    @for($j = 0; $j < count($algoritma_proses[$i]["random1_crossover"]); $j++)
                    <p>{{ $j+1 }}. Random[{{ $j+1 }}] = {{ $algoritma_proses[$i]['random1_crossover'][$j] }}.</p>
                    @endfor
                  </div>
                  <p> 2. Individu Terpilih</p>
                  <div class="pl-2">
                    @for($j = 0; $j < count($algoritma_proses[$i]["index_best_individu"]); $j++)
                    <p>{{ $j+1 }}. Individu[{{ $algoritma_proses[$i]["index_best_individu"][$j] + 1 }}].</p>
                    @endfor
                  </div>
                  <p> 3. Individu dipasangkan dua-dua.</p>
                  <div class="pl-2">
                    @for($j = 0; $j < count($algoritma_proses[$i]["parents"]); $j++)
                    <p>{{ $j+1 }}. Individu[{{ $algoritma_proses[$i]["parents"][$j]['father'] + 1 }}] >< Individu[{{ $algoritma_proses[$i]["parents"][$j]['mother'] + 1 }}].</p>
                    @endfor
                  </div>
                  <p> 4. Menentukan posisi one cut point secara acak.</p>
                  <div class="pl-2">
                    @for($j = 0; $j < count($algoritma_proses[$i]["parents"]); $j++)
                    <p>{{ $j+1 }}. Individu[{{ $algoritma_proses[$i]["parents"][$j]['father'] + 1 }}] >< Individu[{{ $algoritma_proses[$i]["parents"][$j]['mother'] + 1 }}] <span class="text-maroon">(Crossover[{{ $j+1}}])</span> = {{ $algoritma_proses[$i]["parents"][$j]['cut-point'] }}.</p>
                    @endfor
                  </div>

                  @for($j = 0; $j < count($algoritma_proses[$i]["parents"]); $j++)

                  <?php
                  $stringFatherIndividu = "";
                  $stringMotherIndividu = "";
                  $stringOffSpringIndividu = "";

                  foreach ($algoritma_proses[$i]["new_individu_selection"][$algoritma_proses[$i]["parents"][$j]['father']] as $kromosom) {
                    $stringFatherIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                  }
                  foreach ($algoritma_proses[$i]["new_individu_selection"][$algoritma_proses[$i]["parents"][$j]['mother']] as $kromosom) {
                    $stringMotherIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                  }
                  foreach ($algoritma_proses[$i]["offSpring"][$j] as $kromosom) {
                    $stringOffSpringIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                  }

                  $stringFatherIndividu = '{'.substr($stringFatherIndividu, 0, -1).'}'; 
                  $stringMotherIndividu = '{'.substr($stringMotherIndividu, 0, -1).'}';
                  $stringOffSpringIndividu = '{'.substr($stringOffSpringIndividu, 0, -1).'}'; 

                  ?>

                  <p> Proses Crossover ke-{{ $j+1 }}.</p>
                  Individu[{{ $algoritma_proses[$i]["parents"][$j]['father'] + 1 }}] = {{ $stringFatherIndividu }} <br>
                  Individu[{{ $algoritma_proses[$i]["parents"][$j]['mother'] + 1 }}] = {{ $stringMotherIndividu }}<br>
                  Offspring {{ $j+1 }} = {{ $stringOffSpringIndividu }}<br><br>
                  @endfor

                  <p> Individu Baru Hasil Crossover.</p>

                  @for($j=0; $j<count($algoritma_proses[$i]["new_individu_crossover"]); $j++)
                    <?php
                      $stringNewIndividu = "";
                      foreach ($algoritma_proses[$i]["new_individu_crossover"][$j] as $kromosom) {
                        $stringNewIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                      }
                      $stringNewIndividu = '{'.substr($stringNewIndividu, 0, -1).'}';
                    ?>
                    <p>{{ $j+1 }}. Individu[{{ $j+1 }}] = {{ $stringNewIndividu }}.</p>
                  @endfor
                    
                  <p> Hitung Fitness Function Individu Baru.</p>

                  <table class="text-whiteTheme">
                    <tr>
                      <td rowspan="2" class="pr-2">Fitness Function = </td>
                      <td style="border-bottom:solid 1px #FDE8CD;" class="text-center">1</td>
                    </tr>
                    <tr>
                      <td class="text-center">1+( CD+CR )</td>
                    </tr>
                    @foreach($algoritma_proses[$i]['new_fitness_individu'] as $fitness_individu)
                      <tr>
                        <td rowspan="2" class="pr-2">Fitness Individu {{ $loop->iteration }}  : </td>
                        <td style="border-bottom:solid 1px #FDE8CD;" class="text-center">1</td>
                        <td rowspan="2" class="pl-2"> = {{ round($fitness_individu,2) }}</td>
                      </tr>
                      <tr>
                        <td class="text-center">1+( {{ $algoritma_proses[$i]['new_CD'][$loop->index] }} + {{ $algoritma_proses[$i]['new_CR'][$loop->index] }})</td>
                      </tr>
                    @endforeach
                    </table>
                    <p class="mt-1 text-whiteTheme">Total Nilai Fitness = {{ round($algoritma_proses[$i]['new_total_fitness'], 2) }}</p>

                </div>
              </div>
              @endif

              @if(array_key_exists("all_clash_chromosome",$algoritma_proses[$i]))
              <div class="row justify-content-center align-items-center">
                <div class="col-md-12">
                  <p class="text-whiteTheme">PROSES 5 : MUTASI</p>
                </div>
              </div> 
              <div class="row text-whiteTheme">
                <div class="col-md-12">
                  <?php
                    $stringClashChromosom = "";
                    $stringMutatedChromosom = "";

                    foreach ($algoritma_proses[$i]["all_clash_chromosome"] as $kromosom) {
                      $stringClashChromosom .= '['.$kromosom['kromosom'][0].','.$kromosom['kromosom'][1].','.$kromosom['kromosom'][2].'],';
                    }

                    foreach ($algoritma_proses[$i]["mutated_chromosome"] as $kromosom) {
                      $stringMutatedChromosom .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                    }

                    $stringClashChromosom = '{'.substr($stringClashChromosom, 0, -1).'}'; 
                    $stringMutatedChromosom = '{'.substr($stringMutatedChromosom, 0, -1).'}'; 

                    ?>


                  <p>Total Kromosom Bentrok : {{ count($algoritma_proses[$i]["all_clash_chromosome"]) }}</p>
                  <p>Kromosom Bentrok : {{ $stringClashChromosom }}</p>
                  <p>Mutasi Kromosom Bentrok  : {{ $stringMutatedChromosom }}</p>
                  <p> Individu Baru Hasil Mutasi.</p>

                  @for($j=0; $j<count($algoritma_proses[$i]["new_individu_crossover"]); $j++)
                    <?php
                      $stringNewIndividu = "";
                      foreach ($algoritma_proses[$i]["new_individu_has_mutated"][$j] as $kromosom) {
                        $stringNewIndividu .= '['.$kromosom[0].','.$kromosom[1].','.$kromosom[2].'],';
                      }

                      $stringNewIndividu = '{'.substr($stringNewIndividu, 0, -1).'}';

                    ?>
                    <p>{{ $j+1 }}. Individu[{{ $j+1 }}] = {{ $stringNewIndividu }}.</p>
                  @endfor

                </div>
              </div>
              @endif
            </div>
          @endfor
        @endif

        @if(isset($fixJadwal))
        @if(count($fixJadwal) > 0)
        {{ session()->put('jadwal',$fixJadwal) }}
        {{ session()->put('kodeSemester',$kodeSemester) }}
        {{ session()->put('tahunAjaran',$tahunAjaran) }}
            <div class="row mt-4">
              <div class="col-md-12">
                <div class="container">
                  <h2 class="text-center text-whiteTheme bg-choTheme">Jadwal Ditemukan</h2>
                  <p class="h4 text-center text-whiteTheme bg-choTheme mb-2">Waktu Eksekusi : {{ number_format((float)$execution_time, 2, '.', '') }} Detik</p>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="container">
                        @foreach($fixJadwal as $individu)
                          <table class="table table-bordered table-hover text-center bg-light">
                            <thead>
                              <tr>
                              <th scope="col" colspan="7">Jadwal {{ $loop->iteration }}</th>
                              </tr>
                              <tr  class="bg-greenTheme text-whiteTheme">
                                <th scope="col" rowspan="2" style="max-width: 80px; font-size: 18px;">Kromosom ke -</th>
                                <th scope="col" colspan="3">Gen 1</th>
                                <th scope="col">Gen 2</th>
                                <th scope="col" colspan="2">Gen 3</th>
                              </tr>
                              <tr>
                                <th scope="col">matkul</th>
                                <th scope="col">Dosen</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Ruang</th>
                                <th scope="col">Hari</th>
                                <th scope="col">Jam</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($individu as $kromosom)
                              <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $kromosom['kode_matkul'] }}</td>
                                <td>{{ \App\Models\Dosen::where('kode_dosen', $kromosom['kode_dosen']['kode'])->first()->nidn }}</td>
                                <td>{{ $kromosom['kode_kelas'] }}</td>
                                <td>{{ ucwords($kromosom['nama_ruang']['kode']) }}</td>
                                <td>{{ $kromosom['kode_hari'] }}</td>
                                <td>{{ $kromosom['kode_jam'] }}</td>
                              </tr>
                              @endforeach
                              <tr scope="row">
                                <th scope="col" colspan="7"><a href="/hasilgenerate/{{ $loop->index }}"class="btn bg-maroon text-center"><i class="fas fa-table mr-1"></i> Gunakan Jadwal</a></th>
                              </tr>
                            </tbody>
                          </table>
                        @endforeach
                      </div>
                    </div>
                  </div>      
                </div>
              </div>
            </div>
        @endif
        @if(count($fixJadwal) == 0)
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="container">
              <h2 class="text-center text-whiteTheme bg-choTheme">Jadwal Tidak Ditemukan</h2>
              <p class="h4 text-center text-whiteTheme bg-choTheme">Waktu Eksekusi : {{ number_format((float)$execution_time, 2, '.', '') }} Detik</p>
              <a href="#"><p class="h4 text-center text-whiteTheme bg-maroon mb-2" style="cursor: pointer">Silahkan Generate Kembali</p></a>
              <div class="row">
                <div class="col-md-12">
                  <div class="container">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif
        @endif



  <!-- Modal -->
  <div class="modal fade" id="algoritmagenetikaDetail" tabindex="-1" aria-labelledby="algoritmagenetikaDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-greenTheme text-whiteTheme">
          <h5 class="modal-title" id="algoritmagenetikaDetailLabel">Algoritma Genetika</h5>
          <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body detail-container">
          <img src="/img/ga.png" class="img-fluid mb-3" alt="Algoritma Genetika">
          <h2>Apa Itu Algoritma Genetika ?</h2>
          <p class="text-justify">Algoritma Genetika merupakan Teknik untuk menemukan solusi optimal dari permasalahan yang mempunyai banyak solusi. Teknik ini akan melakukan pencarian dari beberapa solusi yang diperoleh sampai mendapatkan solusi terbaik sesuai dengan kriteria yang telah ditentukan atau yang disebut sebagai fungsi fitness.</p>
          <p class="text-justify">Algoritma ini masuk dalam kelompok algoritma evolusioner dengan menggunakan pendekatan evolusi Darwin di bidang Biologi seperti pewarisan sifat, seleksi alam, mutasi gen dan kombinasi (crossover).  Karena merupakan Teknik pencarian optimal dalam bidang ilmu komputer, maka algoritma ini juga termasuk dalam kelompok algoritma metaheuristik.</p>
          <img src="/img/genetika-1.png" class="img-fluid mb-3" alt="Algoritma Genetika">
          <p class="text-justify">Ada beberapa istilah pada Algoritma Genetika seperti populasi, individu, kromosom, gen dan allelle.</p>
          <ul class="mt-n1">
            <li>Gen : Sebuah nilai yang menyatakan satuan dasar yang membentuk suatu arti tertentu dalam satu kesatuan gen yang dinamakan kromosom.</li>
            <li>Allelle : Nilai dari gen.</li>
            <li>Kromosom : Gabungan gen-gen yang membentuk nilai tertentu.</li>
            <li>Individu : Menyatakan satu nilai atau keadaan yang menyatakan salah satu solusi yang mungkin dari permasalahan yang diangkat.</li>
            <li>Populasi : Merupakan sekumpulan individu yang akan diproses bersama dalam satu siklus proses evolusi.</li>
            <li>Generasi : Menyatakan satu siklus proses evolusi atau satu iterasi di dalam algoritma genetika.</li>
          </ul>

          <img src="/img/genetika-2.png" class="img-fluid mb-3" alt="Algoritma Genetika">
          <p class="text-justify">Nah, yang menjadi pertanyaannya bagaimana teori genetika diterapkan pada penjadwalan perkuliahan? jadi, gen-gen tadi representasi penjadwalan Kuliah dengan sub gen Mata Kuliah, dosen dan kelas, ruang dan waktu dengan sub gen hari dan jam seperti gambar diatas.</p>
          <p class="text-justify">Yang menjadi perhatian disini yaitu pada sub gen dari Kuliah yaitu <span class="text-maroon font-weight-bold">Dosen</span> dan gen <span class="text-maroon font-weight-bold">Ruang</span>, algoritma genetika akan mengatur sedemikian rupa agar tidak terjadinya clash(bentrok) antara kedua gen tersebut, <span class="text-maroon">yang berarti <span class="text-maroon font-weight-bold">Dosen</span> dan <span class="text-maroon font-weight-bold">Ruang</span> tidak dapat secara bersamaan ada atau digunakan oleh kelas lain pada waktu bersamaan ataupun sks dari matkul yang belum selesai.</span></p>

          <img src="/img/fase-genetika.png" class="img-fluid mb-3" alt="Algoritma Genetika">
          <p class="text-justify">Terdapat 5 fase dari Algoritma Genetika.</p>
          <ul class="mt-n1 text-justify">
            <li>1.Inisialisasi Populasi : Input populasi awal / jadwal-jadwal yang kemungkinan masih terdapat bentrok.</li>
            <li>2.Fitness Function : Digunakan untuk mengevaluasi apakah jadwal sudah tepat(tidak ada / masih ada yang bentrok), jika ada lanjut ke proses selection.</li>
            <li>3.Selection : Diggunakan untuk mencari individu-individu terbaik berdasarkan fitness value, setelah dapat individunya lanjut ke proses crossover.</li>
            <li>4.Crossover : persilangan masing-masing individu terbaik, individu tersebut disebut parent, dan hasil persilangan individu tersebut disebut child, child ini akan menggantikan parentnya agar mendapatkan individu terbaik, jika individu ini (jadwal) belum tepat (masih ada yang bentrok), maka dilanjutkan ke proses mutasi.</li>
            <li>5.Mutasi : Dilakukan pergantian gen (ruang atau waktu) sehingga rangkaian kromosom pada individu berbeda dari sebelumnya, dengan harapan didapatkan individu(jadwal) yang tepat, kemudian child mutated(individu yang sudah di mutasi) dikembalikan ke populasi menggantikan individu-individu sebelumnya dan dilakukan proses dari awal lagi jika belum didapatkan individu(jadwal) yang pas.</li>
          </ul>
        </div>
        <div class="modal-footer" style="border-top: solid 1px #00917C">
          <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Oke Paham<i class="fas fa-thumbs-up ml-1"></i></button>
        </div>
      </div>
    </div>
  </div> 

  <div class="modal fade" id="individuDetail" tabindex="-1" aria-labelledby="individuDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-greenTheme text-whiteTheme">
          <h5 class="modal-title" id="individuDetailLabel">Individu</h5>
          <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body detail-container">
          <img src="/img/individu.png" class="img-fluid mb-3" alt="Individu">
          <h2>Apa Itu Individu?</h2>
          <p class="text-justify">Didalam Algoritma Genetika <span class="text-maroon font-weight-bold">Individu</span> menyatakan satu nilai atau keadaan yang menyatakan salah satu solusi yang mungkin dari permasalahan yang diangkat. <span class="text-maroon">Singkatnya individu adalah solusi, dan pada program penjadwalan perkuliahan ini, individu adalah jadwal dari perkuliahan.</span></p>
          <p class="text-justify">Dalam prosesnya, dibutuhkan beberapa individu yang perlu dibangkitkan, dan individu-individu yang dibangkitkan inilah yang akan diproses untuk mendapatkan jadwal perkuliahan tanpa adanya clash (bentrok).</p>
        </div>
        <div class="modal-footer" style="border-top: solid 1px #00917C">
          <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Oke Paham<i class="fas fa-thumbs-up ml-1"></i></button>
        </div>
      </div>
    </div>
  </div> 
  
  <div class="modal fade" id="generasiDetail" tabindex="-1" aria-labelledby="generasiDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-greenTheme text-whiteTheme">
          <h5 class="modal-title" id="generasiDetailLabel">Generasi</h5>
          <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body detail-container">
          <img src="/img/fase-genetika.png" class="img-fluid mb-3" alt="Individu">
          <h2>Apa Itu Generasi?</h2>
          <p class="text-justify">Didalam Algoritma Genetika <span class="text-maroon font-weight-bold">Generasi</span> menyatakan satu siklus proses evolusi atau satu iterasi di dalam algoritma genetika. <span class="text-maroon">Singkatnya generasi adalah satu kali proses dari Algoritma Genetika, dan biasanya untuk mendapatkan individu yang bagus ( jadwal yang tidak terdapat bentrok) dibutuhkan beberapa generasi.</span></p>
        </div>
        <div class="modal-footer" style="border-top: solid 1px #00917C">
          <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Oke Paham<i class="fas fa-thumbs-up ml-1"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="crossOverDetail" tabindex="-1" aria-labelledby="crossOverDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-greenTheme text-whiteTheme">
          <h5 class="modal-title" id="crossOverDetailLabel">Cross Over Rate</h5>
          <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body detail-container">
          <img src="/img/crossover.png" class="img-fluid mb-3" alt="Individu">
          <h2>Apa Itu Cross Over Rate?</h2>
          <p class="text-justify"><span class="text-maroon font-weight-bold">Cross Over (pindah silang)</span> adalah proses pemilihan posisi string secara acak dan menukar karakter-karakter stringnya (Goldberg, 1989). <span class="text-maroon"> Fungsi crossover</span> adalah menghasilkan kromosom anak dari kombinasi materi-materi gen dua kromosom induk.</p> 
          <p class="text-justify"><span class="text-maroon">Singkatnya, crossover adalah pertukaran kromosom antara dua individu. Dan Cross Over Rate / Probabilitas Crossover (PC) ditentukan untuk mengendalikan frekuensi crossover, semakin besar nilai cross over rate semakin besar kemungkinan banyaknya crossover yang dibangkitkan.</span></p>
        </div>
        <div class="modal-footer" style="border-top: solid 1px #00917C">
          <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Oke Paham<i class="fas fa-thumbs-up ml-1"></i></button>
        </div>
      </div>
    </div>
  </div>

  @for ($i = 0; $i < count($algoritma_proses); $i++)

  @foreach($algoritma_proses[$i]['individuWithDetail_with_name'] as $individu)
  <?php $individuIndex = $loop->index ?>

  @foreach($individu as $kromosom)

  <div class="modal fade" id="detail-{{ $i }}-{{ $individuIndex }}-{{ $kromosom['kode_kelas'] }}" tabindex="-1" aria-labelledby="detailJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-greenTheme text-whiteTheme">
                <h5 class="modal-title" id="detailJadwalLabel"><i class="fas fa-info-circle mr-2"></i>Detail Individu ke-{{ $individuIndex + 1 }}, kromosom ke-{{ $loop->iteration }}</h5>
                <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="sks">Jumlah SKS</label>
                <input name="sks" type="text" disabled class="form-control" id="sks" value="{{ $kromosom['jumlah_sks'] }}">
            </div>
            <p class="text-center h4 text-maroon">- GEN 1 -</p>
            <div class="form-group">
                <label for="matkul">Mata Kuliah</label>
                <input name="matkul" type="text" disabled class="form-control" id="matkul" value="{{ ucwords($kromosom['matkul']) }}">
            </div>
            <div class="form-group">
                <label for="dosen">Dosen Pengajar</label>
                <input name="dosen" type="text" disabled class="form-control" id="dosen" value="{{ ucwords($kromosom['dosen']) }}">
            </div>
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <input name="kelas" type="text" disabled class="form-control" id="kelas" value="{{ $kromosom['kelas'] }}">
            </div>
            <p class="text-center h4 text-maroon">- GEN 2 -</p>
            <div class="form-group">
              <label for="ruang">Ruangan</label>
              <input name="ruang" type="text" disabled class="form-control" id="ruang" value="{{  ucwords($kromosom['nama_ruang']) }}">
            </div>
            <p class="text-center h4 text-maroon">- GEN 3 -</p>
            <div class="form-group">
              <label for="hari">Hari</label>
              <input name="hari" type="text" disabled class="form-control" id="hari" value="{{  ucwords($kromosom['hari']) }}">
            </div>
            <div class="form-group">
              <label for="jam">Jam</label>
              <input name="jam" type="text" disabled class="form-control" id="jam" value="{{ $kromosom['jam'] }}">
            </div>

            </div>
            <div class="modal-footer" style="border-top: solid 1px #00917C">
                <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

  @endforeach

 
  @endforeach

  @endfor


  <!-- /.content-wrapper -->
  @endsection