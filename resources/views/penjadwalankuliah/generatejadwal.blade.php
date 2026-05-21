@extends('layouts.app')

@section('title','Generate Jadwal | Sistem Penjadwalan Kuliah')

@section('content')
<style>
  .ga-page-title {
    line-height: 1.25;
    font-weight: 700;
  }

  .ga-form-help {
    font-size: 12px;
    color: #6c757d;
    margin-top: 6px;
    line-height: 1.45;
  }

  .ga-process-box {
    background: #1f1f1f;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 28px;
  }

  .ga-result-container {
    width: 100%;
    max-width: none;
    padding: 0;
  }

  .ga-result-header {
    background: #111;
    color: #fff;
    border-radius: 14px 14px 0 0;
    padding: 18px 20px;
    margin-top: 18px;
    text-align: center;
  }

  .ga-result-header h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
  }

  .ga-result-header p {
    margin: 8px 0 0;
    font-size: 18px;
    font-weight: 600;
  }

  .ga-table-card {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    box-shadow: 0 8px 22px rgba(0, 0, 0, .06);
    margin-bottom: 28px;
    overflow: hidden;
  }

  .ga-table-wrapper {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
  }

  .ga-table {
    width: 100%;
    min-width: 1120px;
    margin-bottom: 0 !important;
    table-layout: fixed;
    font-size: 13px;
  }

  .ga-table th,
  .ga-table td {
    vertical-align: middle !important;
    white-space: normal !important;
    word-break: normal;
    overflow-wrap: break-word;
    padding: 10px 7px !important;
  }

  .ga-table thead th {
    background: #4774ad !important;
    color: #fff !important;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .2px;
    text-align: center;
  }

  .ga-table thead tr:first-child th {
    background: #f8f9fa !important;
    color: #212529 !important;
    text-transform: none;
    font-size: 14px;
    font-weight: 700;
  }

  .ga-col-no { width: 42px; }
  .ga-col-matkul { width: 145px; }
  .ga-col-dosen { width: 150px; }
  .ga-col-kelas { width: 45px; }
  .ga-col-ruang { width: 145px; }
  .ga-col-kapasitas { width: 65px; }
  .ga-col-hari { width: 65px; }
  .ga-col-jam { width: 55px; }
  .ga-col-status { width: 88px; }

  .ga-dosen-list {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    text-align: center;
    line-height: 1.35;
    padding: 0 8px;
  }

  .ga-dosen-item {
    display: block;
    width: 100%;
    text-align: center;
  }

  .ga-cell-danger {
    background: #fff0f4 !important;
    color: #b0003a !important;
    font-weight: 700;
  }

  .ga-status-valid {
    display: inline-block;
    min-width: 72px;
    font-size: 11px;
    padding: 6px 8px;
    border-radius: 999px;
  }

  .ga-action-row {
    padding: 14px;
    background: #f8f9fa;
    text-align: center;
  }

  .ga-title {
    background: #111;
    color: #fff;
    padding: 14px;
    margin-bottom: 0;
  }

  .ga-subtitle {
    background: #111;
    color: #fff;
    padding: 8px;
    margin-bottom: 16px;
  }


  .ga-process-card {
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    box-shadow: 0 8px 22px rgba(0, 0, 0, .06);
    margin: 20px 0 28px;
    overflow: hidden;
  }

  .ga-process-header {
    background: #343a40;
    color: #fff;
    padding: 14px 18px;
  }

  .ga-process-header h4 {
    margin: 0;
    font-weight: 700;
  }

  .ga-process-header small {
    color: #d8dee4;
  }

  .ga-process-body {
    padding: 16px;
  }

  .ga-process-note {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 12px;
  }

  .ga-process-table {
    min-width: 1120px;
    font-size: 12px;
    margin-bottom: 0 !important;
  }

  .ga-process-table th {
    background: #4774ad !important;
    color: #fff !important;
    text-align: center;
    vertical-align: middle !important;
  }

  .ga-process-table td {
    vertical-align: middle !important;
  }

  .ga-final-summary {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 14px;
  }

  .ga-final-summary strong {
    color: #212529;
  }

  @media (max-width: 768px) {
    .ga-result-header h2 {
      font-size: 22px;
    }

    .ga-result-header p {
      font-size: 15px;
    }

    .ga-table {
      min-width: 1120px;
    }
  }
</style>

@php
  /**
   * View ini sekarang hanya menampilkan hasil GA yang dikirim controller.
   * Tidak ada normalisasi/penyusunan ulang jadwal di view, supaya hasil akhir tetap murni dari proses algoritma genetika.
   */
  $normalHari = function ($namaHari) {
      $namaHari = strtolower(trim((string) $namaHari));
      $namaHari = str_replace(["'", '`', '’'], '', $namaHari);
      return $namaHari;
  };

  $formatHari = function ($namaHari) use ($normalHari) {
      $normal = $normalHari($namaHari);
      $map = [
          'senin' => 'Senin',
          'selasa' => 'Selasa',
          'rabu' => 'Rabu',
          'kamis' => 'Kamis',
          'jumat' => 'Jumat',
          'sabtu' => 'Sabtu',
          'minggu' => 'Minggu',
      ];
      return $map[$normal] ?? ucwords((string) $namaHari);
  };

  $fmt = function ($value, $decimals = 6) {
      if ($value === null || $value === '') {
          return '-';
      }
      if (is_numeric($value)) {
          return number_format((float) $value, $decimals, '.', '');
      }
      return $value;
  };

  $fixJadwalSiapPakai = [];
  if (isset($fixJadwal) && is_array($fixJadwal) && count($fixJadwal) > 0) {
      $fixJadwalSiapPakai = $fixJadwal;
  }

  $finalProses = null;
  if (isset($algoritma_proses) && is_array($algoritma_proses) && array_key_exists('final', $algoritma_proses)) {
      $finalProses = $algoritma_proses['final'];
  }
@endphp

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-9">
        <h1 class="m-0 ga-page-title">
          Generate Jadwal Perkuliahan Menggunakan
          <span class="text-maroon">Algoritma Genetika</span>
        </h1>
      </div>
      <div class="col-sm-3">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item active">Generate Jadwal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @if (session('status'))
      <div class="row">
        <div class="col-md-6">
          <div class="alert alert-dismissible fade show bg-lime" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        </div>
      </div>
    @endif

    <div class="row">
      <div class="col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Generate Jadwal Perkuliahan</h3>
          </div>

          <form method="post" action="/generatejadwal">
            @csrf

            <div class="card-body">

              <div class="form-group">
                <label>Jumlah Alternatif Jadwal</label>
                <select name="individu" class="form-control select2bs4">
                  @foreach(range(4, 50) as $v)
                    <option value="{{ $v }}">{{ $v }}</option>
                  @endforeach
                </select>
                <div class="ga-form-help">
                  Semakin besar jumlahnya, sistem akan mencoba lebih banyak kemungkinan jadwal.
                  Hasil bisa lebih bagus, tetapi proses bisa sedikit lebih lama.
                </div>
                @if (Session::has('errorJumlahIndividu'))
                  <p class="error-msg">{{ Session::get('errorJumlahIndividu') }}</p>
                @endif
              </div>

              <div class="form-group">
                <label>Jumlah Iterasi Pencarian</label>
                <select name="generasi" class="form-control select2bs4">
                  @foreach(range(10, 500) as $v)
                    <option value="{{ $v }}">{{ $v }}</option>
                  @endforeach
                </select>
                <div class="ga-form-help">
                  Menentukan seberapa lama sistem mencari jadwal terbaik.
                  Semakin besar nilainya, peluang mendapatkan jadwal yang lebih baik meningkat, tetapi waktu proses juga bertambah.
                </div>
                @if (Session::has('errorJumlahGenerasi'))
                  <p class="error-msg">{{ Session::get('errorJumlahGenerasi') }}</p>
                @endif
              </div>

              <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran" id="the_tahun_ajaran" class="form-control select2bs4">
                  <option value="">-- Silahkan Pilih Tahun Ajaran --</option>
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

              <div class="form-group">
                <label>Tingkat Kombinasi Solusi</label>
                <select name="crossover_rate" class="form-control select2bs4">
                  @foreach(range(1, 75) as $v)
                    <option value="{{ $v }}" {{ $v == 75 ? 'selected' : '' }}>{{ $v }}</option>
                  @endforeach
                </select>
                <div class="ga-form-help">
                  Mengatur seberapa sering sistem menggabungkan beberapa kemungkinan jadwal untuk membuat solusi baru.
                  Nilai tinggi membuat sistem lebih aktif mencoba kombinasi.
                </div>
              </div>

              <div class="form-group d-inline">
                <label class="switch">
                  <input type="checkbox" name="algoritma" id="algoritma">
                  <span class="slider"></span>
                </label>
                <label class="ml-1" for="algoritma" style="cursor: pointer">
                  Tampilkan Proses Algoritma
                </label>
              </div>

            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-greenTheme float-right genBtn">
                <i class="fas fa-dna mr-2"></i>Generate Jadwal
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>

    @if(isset($algoritma_proses) && is_array($algoritma_proses) && count($algoritma_proses) > 0)
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="ga-process-card">
            <div class="ga-process-header">
              <h4><i class="fas fa-dna mr-2"></i>Ringkasan Proses Algoritma Genetika</h4>
              <small>Data yang ditampilkan adalah ringkasan per generasi agar proses tetap ringan dan tidak timeout.</small>
            </div>
            <div class="ga-process-body">
              @if($finalProses)
                <div class="ga-final-summary">
                  <div class="row">
                    <div class="col-md-3"><strong>Generasi dijalankan:</strong> {{ $finalProses['generasi_dijalankan'] ?? '-' }}</div>
                    <div class="col-md-3"><strong>Fitness terbaik:</strong> {{ $fmt($finalProses['fitness_terbaik'] ?? null) }}</div>
                    <div class="col-md-3"><strong>Jumlah jadwal:</strong> {{ $finalProses['jumlah_jadwal_ditampilkan'] ?? '-' }}</div>
                    <div class="col-md-3"><strong>Status:</strong> {{ ($finalProses['is_fallback'] ?? 0) ? 'Jadwal terbaik sementara' : 'Jadwal terbaik GA' }}</div>
                  </div>
                  <div class="mt-2 text-muted">{{ $finalProses['catatan'] ?? '' }}</div>
                </div>
              @endif

              <div class="ga-process-note">
                Kolom CD, CR, dan CK adalah komponen penalty: CD = bentrok dosen/blocking, CR = bentrok ruang/kapasitas, CK = bentrok kelas/waktu/sebaran hari.
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover text-center ga-process-table">
                  <thead>
                    <tr>
                      <th>Generasi</th>
                      <th>Individu</th>
                      <th>Kromosom</th>
                      <th>Fitness Awal Terbaik</th>
                      <th>CD</th>
                      <th>CR</th>
                      <th>CK</th>
                      <th>Selection</th>
                      <th>Crossover</th>
                      <th>Mutasi</th>
                      <th>Repair</th>
                      <th>Fitness Global</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($algoritma_proses as $key => $proses)
                      @if($key === 'final')
                        @continue
                      @endif
                      <tr>
                        <td>{{ $proses['generasi'] ?? ((int) $key + 1) }}</td>
                        <td>{{ $proses['jumlah_individu'] ?? '-' }}</td>
                        <td>{{ $proses['jumlah_kromosom'] ?? '-' }}</td>
                        <td>{{ $fmt($proses['fitness_awal_terbaik'] ?? null) }}</td>
                        <td>{{ $proses['cd_awal_terbaik'] ?? '-' }}</td>
                        <td>{{ $proses['cr_awal_terbaik'] ?? '-' }}</td>
                        <td>{{ $proses['ck_awal_terbaik'] ?? '-' }}</td>
                        <td>
                          {{ $proses['selection']['metode'] ?? '-' }}<br>
                          <small>Terpilih: {{ $proses['selection']['jumlah_terpilih'] ?? 0 }}</small>
                        </td>
                        <td>
                          PC: {{ $fmt($proses['crossover']['pc'] ?? null, 4) }}<br>
                          <small>Parent: {{ $proses['crossover']['jumlah_parent'] ?? 0 }}, Offspring: {{ $proses['crossover']['jumlah_offspring'] ?? 0 }}</small>
                        </td>
                        <td>
                          Bentrok: {{ $proses['mutation']['jumlah_kromosom_bentrok'] ?? 0 }}<br>
                          <small>Dimutasi: {{ $proses['mutation']['jumlah_kromosom_dimutasi'] ?? 0 }}</small>
                        </td>
                        <td>
                          {{ $proses['repair']['operator'] ?? '-' }}<br>
                          <small>Individu: {{ $proses['repair']['jumlah_individu_repair'] ?? 0 }}</small>
                        </td>
                        <td>{{ $fmt($proses['fitness_global_terbaik'] ?? null) }}</td>
                        <td class="text-left">{{ $proses['status'] ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    @if(isset($fixJadwal))
      @if(count($fixJadwalSiapPakai) > 0)

        <div class="row mt-4">
          <div class="col-md-12">
            <div class="ga-result-container">
              <div class="ga-result-header">
                <h2>Jadwal Ditemukan</h2>
                <p>Waktu Eksekusi : {{ number_format((float)$execution_time, 2, '.', '') }} Detik</p>
              </div>

              @foreach($fixJadwalSiapPakai as $individu)
                <div class="ga-table-card">
                  <div class="ga-table-wrapper">
                    <table class="table table-bordered table-hover text-center bg-light ga-table">
                      <colgroup>
                        <col class="ga-col-no">
                        <col class="ga-col-matkul">
                        <col class="ga-col-dosen">
                        <col class="ga-col-kelas">
                        <col class="ga-col-ruang">
                        <col class="ga-col-kapasitas">
                        <col class="ga-col-hari">
                        <col class="ga-col-jam">
                        <col class="ga-col-status">
                      </colgroup>

                      <thead>
                        <tr>
                          <th colspan="9">Jadwal {{ $loop->iteration }}</th>
                        </tr>
                        <tr>
                          <th>No</th>
                          <th>Mata Kuliah</th>
                          <th>Dosen Pengajar</th>
                          <th>Kelas</th>
                          <th>Ruang</th>
                          <th>Kapasitas</th>
                          <th>Hari</th>
                          <th>Jam</th>
                          <th>Status</th>
                        </tr>
                      </thead>

                      <tbody>
                        @forelse($individu as $kromosom)
                          @php
                            $namaMatkul = DB::table('matkul')->where('kode_matkul', $kromosom['kode_matkul'] ?? null)->value('nama_matkul');

                            $kodeDosenList = $kromosom['kode_dosen']['list'] ?? [];
                            $kodeDosenList = array_values(array_unique(array_filter($kodeDosenList)));

                            if (count($kodeDosenList) == 0 && isset($kromosom['kode_dosen']['kode'])) {
                              $kodeDosenList = [$kromosom['kode_dosen']['kode']];
                            }

                            $kodeDosenList = array_slice($kodeDosenList, 0, 2);

                            $namaHariDb = DB::table('hari')->where('kode_hari', $kromosom['kode_hari'] ?? null)->value('nama_hari');
                            $namaHari = $formatHari($namaHariDb ?? '-');
                            $jamMulai = DB::table('jam')->where('kode_jam', $kromosom['kode_jam'] ?? null)->value('jam');

                            $isDosenClash = ($kromosom['kode_dosen']['clash'] ?? 0) == 1;
                            $isBlocked = ($kromosom['kode_dosen']['blocked'] ?? 0) == 1;
                            $isRuangClash = ($kromosom['nama_ruang']['clash'] ?? 0) == 1;
                            $isCapacityInvalid = ($kromosom['nama_ruang']['capacity_invalid'] ?? 0) == 1;
                            $isKelasClash = ($kromosom['kelas_clash'] ?? 0) == 1;
                            $isTimeInvalid = ($kromosom['time_invalid'] ?? 0) == 1;
                          @endphp

                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $namaMatkul ?? ($kromosom['kode_matkul'] ?? '-') }}</td>
                            <td class="{{ $isDosenClash || $isBlocked ? 'ga-cell-danger' : '' }}">
                              <div class="ga-dosen-list">
                                @forelse($kodeDosenList as $kodeDosen)
                                  <span class="ga-dosen-item">{{ $kodeDosen }}</span>
                                @empty
                                  <span class="ga-dosen-item">-</span>
                                @endforelse
                              </div>
                            </td>
                            <td class="{{ $isKelasClash ? 'ga-cell-danger' : '' }}">
                              {{ $kromosom['nama_kelas'] ?? '-' }}
                            </td>
                            <td class="{{ $isRuangClash || $isCapacityInvalid ? 'ga-cell-danger' : '' }}">
                              {{ ucwords($kromosom['nama_ruang']['kode'] ?? '-') }}
                            </td>
                            <td>{{ $kromosom['nama_ruang']['kapasitas'] ?? '-' }}</td>
                            <td>{{ $namaHari }}</td>
                            <td>{{ $jamMulai ? substr((string) $jamMulai, 0, 5) : '-' }}</td>
                            <td>
                              @if($isBlocked)
                                <span class="badge bg-maroon ga-status-valid">Dosen Blocking</span>
                              @elseif($isDosenClash)
                                <span class="badge bg-maroon ga-status-valid">Bentrok Dosen</span>
                              @elseif($isKelasClash)
                                <span class="badge bg-maroon ga-status-valid">Bentrok Kelas</span>
                              @elseif($isRuangClash)
                                <span class="badge bg-maroon ga-status-valid">Bentrok Ruang</span>
                              @elseif($isCapacityInvalid)
                                <span class="badge bg-maroon ga-status-valid">Kapasitas Kurang</span>
                              @elseif($isTimeInvalid)
                                <span class="badge bg-maroon ga-status-valid">Lewat Jam</span>
                              @else
                                <span class="badge bg-lime ga-status-valid">Valid</span>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="9" class="text-danger font-weight-bold">
                              Jadwal kosong. Pastikan setiap kelas memiliki 2 dosen, ruang tersedia, dan waktu perkuliahan sudah lengkap.
                            </td>
                          </tr>
                        @endforelse

                        <tr>
                          <th colspan="9">
                            <div class="ga-action-row">
                              <a href="/hasilgenerate/{{ $loop->index }}" class="btn bg-maroon text-center">
                                <i class="fas fa-table mr-1"></i> Gunakan Jadwal
                              </a>
                            </div>
                          </th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @else
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="container">
              <h2 class="ga-title text-center">Jadwal Tidak Ditemukan</h2>
              <p class="h4 ga-subtitle text-center">
                Waktu Eksekusi : {{ number_format((float)$execution_time, 2, '.', '') }} Detik
              </p>
            </div>
          </div>
        </div>
      @endif
    @endif

  </div>
</section>
@endsection
