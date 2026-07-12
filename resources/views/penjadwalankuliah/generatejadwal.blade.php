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

  /* === Styles imported from hasiljadwal for weekly grid UI === */
  .jadwal-table-wrapper {
    width: 100%;
    overflow-x: auto;
  }

  .jadwal-grid {
    min-width: 1500px;
    table-layout: fixed;
    font-size: 13px;
    margin-bottom: 0 !important;
  }

  .jadwal-grid th,
  .jadwal-grid td {
    text-align: center;
    border: 1px solid #dee2e6 !important;
  }

  .jadwal-grid th {
    background: #4b74ad;
    color: #fff;
    vertical-align: middle !important;
  }

  .jam-col {
    width: 120px;
    font-weight: 700;
    background: #f8f9fa;
    vertical-align: middle !important;
  }

  .jadwal-cell {
    height: 108px;
    min-height: 108px;
    padding: 8px;
    background: #fff;
    vertical-align: top !important;
  }

  .jadwal-item {
    border: 1px solid #d9e2ef;
    border-radius: 8px;
    padding: 7px;
    margin-bottom: 6px;
    background: #f8fbff;
    text-align: left;
    line-height: 1.35;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
    border-left: 4px solid #d9e2ef;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }

  .jadwal-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, .08);
  }

  .jadwal-item:last-child {
    margin-bottom: 0;
  }

  .jadwal-item-wajib {
    background: #BFDBFE;
    border-left-color: #3b82f6;
    border-color: #93c5fd;
  }
  .jadwal-item-wajib .jadwal-matkul {
    color: #1e3a5f;
  }
  .jadwal-item-wajib .jadwal-dosen {
    color: #1d4ed8;
  }

  .jadwal-item-pilihan {
    background: #BBF7D0;
    border-left-color: #22c55e;
    border-color: #86efac;
  }
  .jadwal-item-pilihan .jadwal-matkul {
    color: #14532d;
  }
  .jadwal-item-pilihan .jadwal-dosen {
    color: #15803d;
  }

  .jadwal-item-praktikum {
    background: #FED7AA;
    border-left-color: #f97316;
    border-color: #fdba74;
  }
  .jadwal-item-praktikum .jadwal-matkul {
    color: #7c2d12;
  }
  .jadwal-item-praktikum .jadwal-dosen {
    color: #c2410c;
  }

  .jadwal-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
    padding: 10px 16px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 14px;
    font-size: 13px;
  }

  .jadwal-legend-title {
    font-weight: 700;
    color: #333;
    margin-right: 4px;
  }

  .jadwal-legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .jadwal-legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid rgba(0,0,0,.12);
    flex-shrink: 0;
  }

  .legend-wajib {
    background: #BFDBFE;
  }
  .legend-pilihan {
    background: #BBF7D0;
  }
  .legend-praktikum {
    background: #FED7AA;
  }

  .jadwal-item-online {
    background: #E9D5FF;
    border-left-color: #8b5cf6;
    border-color: #c4b5fd;
  }
  .jadwal-item-online .jadwal-matkul {
    color: #4c1d95;
  }
  .jadwal-item-online .jadwal-dosen {
    color: #6d28d9;
  }

  .legend-online {
    background: #E9D5FF;
  }

  .jadwal-matkul {
    font-weight: 700;
    text-transform: capitalize;
    color: #111;
  }

  .jadwal-meta {
    font-size: 12px;
    color: #333;
  }

  .jadwal-dosen {
    font-size: 12px;
    font-weight: 700;
    color: #0b5ed7;
  }

  .empty-cell {
    height: 108px;
    min-height: 108px;
    color: #aaa;
    background: #fafafa;
    vertical-align: middle !important;
  }

  .weekend-empty {
    height: 108px;
    min-height: 108px;
    background: #fafafa !important;
    color: inherit;
    text-align: center !important;
    vertical-align: middle !important;
    padding: 0 !important;
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

  $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Jadwal Online'];

  $daysMap = [];
  foreach (DB::table('hari')->get() as $h) {
      $daysMap[$h->kode_hari] = $formatHari($h->nama_hari);
  }

  $matkulMap = [];
  foreach (DB::table('matkul')->get() as $m) {
      $matkulMap[$m->kode_matkul] = $m;
  }

  $ruangMap = [];
  foreach (DB::table('ruang')->get() as $r) {
      $ruangMap[$r->kode_ruang] = $r;
  }

  $normalJenisMatkul = function ($jenis) {
      $jenis = strtolower(trim((string) $jenis));
      return strpos($jenis, 'prakt') !== false ? 'praktikum' : 'teori';
  };

  $normalTipeRuang = function ($tipe) {
      $tipe = strtolower(trim((string) $tipe));
      return strpos($tipe, 'lab') !== false ? 'laboratorium' : 'reguler';
  };

  $jamToMinutes = function ($jam) {
      if (!$jam) {
          return 0;
      }
      $jam = substr((string) $jam, 0, 5);
      if (strpos($jam, ':') === false) {
          return 0;
      }
      $parts = explode(':', $jam);
      $hour = isset($parts[0]) ? (int) $parts[0] : 0;
      $minute = isset($parts[1]) ? (int) $parts[1] : 0;
      return ($hour * 60) + $minute;
  };
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
      <div class="row justify-content-center">
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

    <div class="row justify-content-center">
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
                    <option value="{{ $v }}" {{ $v == 30 ? 'selected' : '' }}>{{ $v }}</option>
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
                    <option value="{{ $v }}" {{ $v == 100 ? 'selected' : '' }}>{{ $v }}</option>
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

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Jam Mulai Kuliah</label>
                    <input type="time" name="jam_mulai" class="form-control" value="07:00" required>
                    <div class="ga-form-help">Waktu paling pagi perkuliahan dimulai.</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Jam Terakhir Mulai Kuliah</label>
                    <input type="time" name="jam_terakhir_mulai" class="form-control" value="17:00" required>
                    <div class="ga-form-help">Batas paling lambat perkuliahan boleh mulai (contoh: 17:00).</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Durasi 1 SKS (Menit)</label>
                    <input type="number" name="durasi_sks" class="form-control" value="50" min="10" required>
                    <div class="ga-form-help">Berapa lama 1 SKS berlangsung (contoh: 50 menit).</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Jeda Antar Kelas (Menit)</label>
                    <input type="number" name="jeda" class="form-control" value="10" min="0" required>
                    <div class="ga-form-help">Istirahat antar mata kuliah.</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mulai Istirahat Siang</label>
                    <input type="time" name="istirahat_mulai" class="form-control" value="12:00" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Selesai Istirahat Siang</label>
                    <input type="time" name="istirahat_selesai" class="form-control" value="13:00" required>
                  </div>
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

    @if(isset($laporanPengujian) && is_array($laporanPengujian))
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="ga-process-card">
            <div class="ga-process-header">
              <h4><i class="fas fa-chart-bar mr-2"></i>Laporan Pengujian Algoritma Genetika</h4>
              <small>Engine ZERO-BENTROK-v5: optimasi final dengan target 0 bentrok. Jadwal yang tidak bisa ditempatkan fisik otomatis masuk Jadwal Online.</small>
            </div>
            <div class="ga-process-body">
              <div class="row mb-3">
                <div class="col-md-3"><strong>Status:</strong><br>{{ $laporanPengujian['status'] ?? '-' }}</div>
                <div class="col-md-3"><strong>Fitness awal terbaik:</strong><br>{{ $fmt($laporanPengujian['fitness_awal_terbaik'] ?? null) }}</div>
                <div class="col-md-3"><strong>Fitness akhir:</strong><br>{{ $fmt($laporanPengujian['fitness_akhir'] ?? null) }}</div>
                <div class="col-md-3"><strong>Waktu eksekusi:</strong><br>{{ $laporanPengujian['waktu_eksekusi'] ?? '-' }} detik</div>
              </div>

              <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm text-center">
                  <thead class="thead-light">
                    <tr>
                      <th>Seed</th>
                      <th>Populasi</th>
                      <th>Maks. Generasi</th>
                      <th>Generasi Dijalankan</th>
                      <th>Crossover Rate</th>
                      <th>Jumlah Jadwal</th>
                      <th>Jadwal Online</th>
                      <th>Target Bentrok Ruang</th>
                      <th>Konflik Awal</th>
                      <th>Konflik Akhir</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $laporanPengujian['seed'] ?? '-' }}</td>
                      <td>{{ $laporanPengujian['jumlah_individu'] ?? '-' }}</td>
                      <td>{{ $laporanPengujian['maksimum_generasi'] ?? '-' }}</td>
                      <td>{{ $laporanPengujian['generasi_dijalankan'] ?? '-' }}</td>
                      <td>{{ $laporanPengujian['crossover_rate'] ?? '-' }}%</td>
                      <td>{{ $laporanPengujian['jumlah_jadwal'] ?? '-' }}</td>
                      <td>{{ $laporanPengujian['jadwal_online_otomatis'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['target_maksimal_bentrok_ruang'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik_awal_terbaik'] ?? '-' }}</td>
                      <td>{{ $laporanPengujian['konflik']['total_jadwal_bermasalah'] ?? '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-sm text-center mb-0">
                  <thead>
                    <tr>
                      <th>Bentrok Dosen</th>
                      <th>Bentrok Ruang</th>
                      <th>Bentrok Rombel</th>
                      <th>Blocking Dosen</th>
                      <th>Kapasitas</th>
                      <th>Tipe Ruang</th>
                      <th>Batas Waktu</th>
                      <th>Aturan Hari</th>
                      <th>CD</th>
                      <th>CR</th>
                      <th>CK</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $laporanPengujian['konflik']['bentrok_dosen'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['bentrok_ruang'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['bentrok_rombel'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['blocking_dosen'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['kapasitas_ruang'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['tipe_ruang'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['batas_waktu'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['konflik']['aturan_hari'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['penalty_cd'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['penalty_cr'] ?? 0 }}</td>
                      <td>{{ $laporanPengujian['penalty_ck'] ?? 0 }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

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
    @endif    @if(isset($fixJadwal))
      @if(count($fixJadwalSiapPakai) > 0)

        <div class="row mt-4">
          <div class="col-md-12">
            <div class="ga-result-container">
              <div class="ga-result-header">
                <h2>Jadwal Ditemukan</h2>
                <p>Waktu Eksekusi : {{ number_format((float)$execution_time, 2, '.', '') }} Detik</p>
              </div>

              @foreach($fixJadwalSiapPakai as $indexAlternatif => $individu)
                @php
                  $individuRows = collect($individu)->map(function ($item, $rowIndex) {
                      $item['_row_index'] = $rowIndex;
                      return $item;
                  });

                  $individuRows = $individuRows->sortBy(function ($item) use ($jamToMinutes) {
                      $mulai = isset($item['jam_mulai']) ? $item['jam_mulai'] : '00:00';
                      $selesai = isset($item['jam_selesai']) ? $item['jam_selesai'] : '00:00';
                      $hari = isset($item['kode_hari']) ? (int) $item['kode_hari'] : 0;
                      return str_pad($jamToMinutes($mulai), 4, '0', STR_PAD_LEFT)
                          . '|'
                          . str_pad($jamToMinutes($selesai), 4, '0', STR_PAD_LEFT)
                          . '|'
                          . $hari;
                  })->values();

                  $jamList = $individuRows->map(function ($item) use ($jamToMinutes) {
                      $mulai = isset($item['jam_mulai']) ? substr((string) $item['jam_mulai'], 0, 5) : '07:00';
                      $selesai = isset($item['jam_selesai']) ? substr((string) $item['jam_selesai'], 0, 5) : '07:50';
                      return [
                          'key' => $mulai . '|' . $selesai,
                          'mulai_menit' => $jamToMinutes($mulai),
                          'keluar_menit' => $jamToMinutes($selesai),
                      ];
                  })->unique('key')->sortBy('mulai_menit')->pluck('key')->values();
                @endphp

                <div class="card card-outline card-success mt-4">
                  <div class="card-header bg-greenTheme text-white text-bold">
                    <h3 class="card-title text-white text-bold">Alternatif Jadwal {{ $loop->iteration }}</h3>
                  </div>

                  {{-- Keterangan Warna / Legend --}}
                  <div style="padding: 14px 16px 0;">
                    <div class="jadwal-legend">
                      <span class="jadwal-legend-title"><i class="fas fa-palette mr-1"></i> Keterangan:</span>
                      <span class="jadwal-legend-item">
                        <span class="jadwal-legend-color legend-wajib"></span> Matkul Wajib
                      </span>
                      <span class="jadwal-legend-item">
                        <span class="jadwal-legend-color legend-pilihan"></span> Matkul Pilihan
                      </span>
                      <span class="jadwal-legend-item">
                        <span class="jadwal-legend-color legend-praktikum"></span> Praktikum
                      </span>
                      <span class="jadwal-legend-item">
                        <span class="jadwal-legend-color legend-online"></span> Jadwal Online
                      </span>
                    </div>
                  </div>

                  <div class="card-body p-0">
                    <div class="jadwal-table-wrapper">
                      <table class="table table-bordered jadwal-grid">
                        <thead>
                          <tr>
                            <th class="jam-col">Jam</th>
                            @foreach($hariList as $hari)
                              <th>{{ $hari }}</th>
                            @endforeach
                          </tr>
                        </thead>

                        <tbody>
                          @forelse($jamList as $jamKey)
                            @php
                              [$jamMasuk, $jamKeluar] = explode('|', $jamKey);

                              $adaJadwalDiSlot = $individuRows->filter(function ($item) use ($jamMasuk, $jamKeluar) {
                                  $itemMulai = isset($item['jam_mulai']) ? substr((string) $item['jam_mulai'], 0, 5) : '07:00';
                                  $itemKeluar = isset($item['jam_selesai']) ? substr((string) $item['jam_selesai'], 0, 5) : '07:50';
                                  return $itemMulai == $jamMasuk && $itemKeluar == $jamKeluar;
                              })->count() > 0;
                            @endphp

                            @if($adaJadwalDiSlot)
                              <tr>
                                <td class="jam-col">
                                  {{ $jamMasuk }}<br>
                                  <span style="font-weight: 400;">s/d</span><br>
                                  {{ $jamKeluar }}
                                </td>

                                @foreach($hariList as $hari)
                                  @php
                                    $items = $individuRows->filter(function ($item) use ($hari, $jamMasuk, $jamKeluar, $daysMap) {
                                        $itemHari = !empty($item['is_online']) ? 'Jadwal Online' : ($daysMap[$item['kode_hari']] ?? '-');
                                        $itemMulai = isset($item['jam_mulai']) ? substr((string) $item['jam_mulai'], 0, 5) : '07:00';
                                        $itemKeluar = isset($item['jam_selesai']) ? substr((string) $item['jam_selesai'], 0, 5) : '07:50';
                                        return $itemHari == $hari
                                            && $itemMulai == $jamMasuk
                                            && $itemKeluar == $jamKeluar;
                                    })->sortBy(function ($item) {
                                        return ($item['nama_kelas'] ?? '') . '|' . ($item['kode_matkul'] ?? '');
                                    });

                                    $isWeekend = in_array($hari, ['Sabtu', 'Minggu']);
                                  @endphp

                                  @if($items->count() > 0)
                                    <td class="jadwal-cell">
                                      @foreach($items as $item)
                                        @php
                                          $mkRecord = $matkulMap[$item['kode_matkul']] ?? null;
                                          $isOnline = !empty($item['is_online']);
                                          $jenisItem = strtolower($item['jenis_matkul'] ?? ($mkRecord->jenis_matkul ?? 'teori'));
                                          $tipeItem  = strtolower($mkRecord->tipe_matkul ?? 'wajib');

                                          if ($isOnline) {
                                              $warnaClass = 'jadwal-item-online';
                                          } elseif ($jenisItem === 'praktikum') {
                                              $warnaClass = 'jadwal-item-praktikum';
                                          } elseif ($tipeItem === 'pilihan') {
                                              $warnaClass = 'jadwal-item-pilihan';
                                          } else {
                                              $warnaClass = 'jadwal-item-wajib';
                                          }

                                          $kodeDosenList = $item['kode_dosen']['list'] ?? [];
                                          $kodeDosenList = array_values(array_unique(array_filter($kodeDosenList)));
                                          if (count($kodeDosenList) == 0 && isset($item['kode_dosen']['kode'])) {
                                              $kodeDosenList = [$item['kode_dosen']['kode']];
                                          }
                                          $kodeDosenList = array_slice($kodeDosenList, 0, 2);
                                          $dosenStr = implode(', ', $kodeDosenList);

                                          $isDosenClash = ($item['kode_dosen']['clash'] ?? 0) == 1;
                                          $isBlocked = ($item['kode_dosen']['blocked'] ?? 0) == 1;
                                          $isRuangClash = ($item['nama_ruang']['clash'] ?? 0) == 1;
                                          $isCapacityInvalid = ($item['nama_ruang']['capacity_invalid'] ?? 0) == 1;
                                          $kodeRuangItem = $item['nama_ruang']['kode_ruang'] ?? null;
                                          $ruangRecord = $kodeRuangItem !== null
                                              ? ($ruangMap[$kodeRuangItem] ?? null)
                                              : null;
                                          $jenisAktual = $normalJenisMatkul(
                                              $mkRecord->jenis_matkul ?? ($item['jenis_matkul'] ?? 'teori')
                                          );
                                          $tipeRuangAktual = $normalTipeRuang(
                                              $ruangRecord->tipe_ruang ?? 'reguler'
                                          );
                                          $isRoomTypeMismatch = (($item['nama_ruang']['room_type_mismatch'] ?? 0) == 1)
                                              || ($jenisAktual === 'teori' && $tipeRuangAktual !== 'reguler')
                                              || ($jenisAktual === 'praktikum' && $tipeRuangAktual !== 'laboratorium');
                                          $isKelasClash = ($item['kelas_clash'] ?? 0) == 1;
                                          $isTimeInvalid = ($item['time_invalid'] ?? 0) == 1;
                                          $isBentrok = $isBlocked || $isDosenClash || $isKelasClash || $isRuangClash || $isCapacityInvalid || $isRoomTypeMismatch || $isTimeInvalid;
                                        @endphp
                                        <div class="jadwal-item {{ $warnaClass }}">
                                          <div class="jadwal-matkul">
                                            {{ $mkRecord->nama_matkul ?? $item['kode_matkul'] }}
                                          </div>
                                          <div class="jadwal-dosen">
                                            {{ $dosenStr }}
                                          </div>
                                          <div class="jadwal-meta">
                                            Kelas {{ $item['nama_kelas'] ?? '-' }} | {{ $item['jumlah_sks'] ?? '-' }} SKS
                                          </div>
                                          <div class="jadwal-meta">
                                            {{ \App\Models\Ruang::formatName($item['nama_ruang']['kode'] ?? '-') }}
                                          </div>
                                          <div class="mt-1 text-center">
                                            @if($isOnline)
                                              <span class="badge bg-purple">Online</span>
                                            @elseif($isBlocked)
                                              <span class="badge bg-maroon">Dosen Blocking</span>
                                            @elseif($isDosenClash)
                                              <span class="badge bg-maroon">Bentrok Dosen</span>
                                            @elseif($isKelasClash)
                                              <span class="badge bg-maroon">Bentrok Kelas</span>
                                            @elseif($isRuangClash)
                                              <span class="badge bg-maroon">Bentrok Ruang</span>
                                            @elseif($isCapacityInvalid)
                                              <span class="badge bg-maroon">Kapasitas Kurang</span>
                                            @elseif($isRoomTypeMismatch)
                                              <span class="badge bg-maroon">Tipe Ruang Salah</span>
                                            @elseif($isTimeInvalid)
                                              <span class="badge bg-maroon">Lewat Jam</span>
                                            @else
                                              <span class="badge bg-lime">Valid</span>
                                            @endif

                                            @if(!$isOnline && $isBentrok)
                                              <form method="POST" action="{{ route('generatejadwal.pindah-online', ['jadwal_index' => $indexAlternatif, 'row_index' => $item['_row_index']]) }}" class="mt-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary btn-block">
                                                  Pindahkan ke Jadwal Online
                                                </button>
                                              </form>
                                            @endif
                                          </div>
                                        </div>
                                      @endforeach
                                    </td>
                                  @else
                                    @if($isWeekend)
                                      <td class="weekend-empty"></td>
                                    @else
                                      <td class="empty-cell">-</td>
                                    @endif
                                  @endif
                                @endforeach
                              </tr>
                            @endif
                          @empty
                            <tr>
                              <td colspan="9" class="jadwal-empty-message">
                                Belum ada data jadwal.
                              </td>
                            </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="card-footer">
                    <div class="ga-action-row">
                      <a href="/hasilgenerate/{{ $indexAlternatif }}" class="btn bg-maroon text-center">
                        <i class="fas fa-table mr-1"></i> Gunakan Jadwal
                      </a>
                    </div>
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
