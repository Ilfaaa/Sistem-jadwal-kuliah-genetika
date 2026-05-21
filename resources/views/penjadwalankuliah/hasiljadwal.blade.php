@extends('layouts.app')

@section('title','Hasil Generate Jadwal | Sistem Penjadwalan Kuliah')

@section('content')

<style>
  .jadwal-table-wrapper {
    width: 100%;
    overflow-x: auto;
  }

  .jadwal-grid {
    min-width: 1350px;
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
  }

  .jadwal-item:last-child {
    margin-bottom: 0;
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
    background: #fde8e8 !important;
    color: #c82333;
    font-weight: 700;
    text-align: center !important;
    vertical-align: middle !important;
    padding: 0 !important;
  }

  .weekend-label-wrap {
    min-height: 108px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px;
    line-height: 1.3;
  }

  .semester-card {
    display: none;
  }

  .jadwal-empty-message {
    padding: 24px;
    text-align: center;
    color: #777;
    font-weight: 600;
  }
</style>

@php
  $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
  $hariAktifList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

  $normalJam = function ($jam) {
      if (!$jam) {
          return '-';
      }

      return substr((string) $jam, 0, 5);
  };

  $jamKeMenit = function ($jam) use ($normalJam) {
      $jam = $normalJam($jam);

      if ($jam === '-' || strpos($jam, ':') === false) {
          return 0;
      }

      [$h, $m] = explode(':', $jam);
      return ((int) $h * 60) + (int) $m;
  };

  $normalHari = function ($hari) {
      $hari = strtolower(trim((string) $hari));
      $hari = str_replace(["'", '`', '’'], '', $hari);

      if ($hari === 'senin') return 'Senin';
      if ($hari === 'selasa') return 'Selasa';
      if ($hari === 'rabu') return 'Rabu';
      if ($hari === 'kamis') return 'Kamis';
      if ($hari === 'jumat') return 'Jumat';
      if ($hari === 'sabu' || $hari === 'sabtu') return 'Sabtu';
      if ($hari === 'minggu') return 'Minggu';

      return ucwords($hari);
  };

  $formatDosen = function ($item) {
      if (isset($item->kode_dosen_list) && $item->kode_dosen_list) {
          return $item->kode_dosen_list;
      }

      if (isset($item->kode_dosen) && $item->kode_dosen) {
          return $item->kode_dosen;
      }

      return $item->dosen ?? '-';
  };

  $urutHari = function ($hari) use ($hariList) {
      $index = array_search($hari, $hariList);
      return $index === false ? 99 : $index;
  };
@endphp

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-9">
        <h1 class="m-0">Jadwal Perkuliahan</h1>
      </div>
      <div class="col-sm-3">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item active">Hasil Jadwal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @if($user_login->role_id == 1)
      <a href="/generatejadwal" class="btn btn-outline-greenTheme mb-2">
        <i class="fas fa-recycle mr-1"></i>Generate Kembali Jadwal
      </a>
    @endif

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="form-group mb-0">
              <label>Tahun Ajaran</label>
              <select name="search-tahun" id="search-tahun" class="form-control select2bs4" style="width: 100%;">
                <option value="" id="default-tahun-option">-- Silahkan pilih Tahun Ajaran --</option>
                @foreach($tahun_ajaran as $tahun)
                  <option value="{{ $tahun->tahun_ajaran }}">{{ $tahun->tahun_ajaran }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    @foreach($semester as $indexSemester => $sms)
      @php
        $jadwalSemester = collect($jadwal[$indexSemester] ?? []);

        $jadwalSemester = $jadwalSemester->map(function ($item) use ($normalHari) {
            $copy = clone $item;
            $copy->hari = $normalHari($copy->hari ?? '');
            return $copy;
        });

        $jadwalByTahun = $jadwalSemester->groupBy('tahun_ajaran');
      @endphp

      @foreach($jadwalByTahun as $tahun => $rows)
        @php
          $rows = collect($rows)
              ->sortBy(function ($item) use ($normalJam, $jamKeMenit, $urutHari) {
                  return str_pad($jamKeMenit($item->jam_masuk ?? '00:00'), 4, '0', STR_PAD_LEFT)
                      . '|'
                      . str_pad($jamKeMenit($item->jam_keluar ?? '00:00'), 4, '0', STR_PAD_LEFT)
                      . '|'
                      . str_pad($urutHari($item->hari ?? ''), 2, '0', STR_PAD_LEFT)
                      . '|'
                      . ($item->kelas ?? '')
                      . '|'
                      . ($item->matkul ?? '');
              })
              ->values();

          $jamList = $rows
              ->map(function ($item) use ($normalJam, $jamKeMenit) {
                  return [
                      'key' => $normalJam($item->jam_masuk) . '|' . $normalJam($item->jam_keluar),
                      'mulai_menit' => $jamKeMenit($item->jam_masuk),
                      'keluar_menit' => $jamKeMenit($item->jam_keluar),
                  ];
              })
              ->unique('key')
              ->sortBy(function ($item) {
                  return str_pad($item['mulai_menit'], 4, '0', STR_PAD_LEFT)
                      . '|'
                      . str_pad($item['keluar_menit'], 4, '0', STR_PAD_LEFT);
              })
              ->pluck('key')
              ->values();
        @endphp

        <div class="card semester-card jadwal-section" data-tahun="{{ $tahun }}">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title">
              Tabel Jadwal Perkuliahan {{ $sms->nama_semester }} <b>{{ $tahun }}</b>
            </h3>
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

                      // Penting: jangan hanya cek Senin-Jumat.
                      // Jika praktikum dipindah ke Sabtu/Minggu, baris jam weekend tetap harus dirender.
                      $adaJadwalDiSlot = $rows->filter(function ($item) use ($jamMasuk, $jamKeluar, $normalJam) {
                          return $normalJam($item->jam_masuk) == $jamMasuk
                              && $normalJam($item->jam_keluar) == $jamKeluar;
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
                            $items = $rows->filter(function ($item) use ($hari, $jamMasuk, $jamKeluar, $normalJam) {
                                return $item->hari == $hari
                                    && $normalJam($item->jam_masuk) == $jamMasuk
                                    && $normalJam($item->jam_keluar) == $jamKeluar;
                            })->sortBy(function ($item) {
                                return ($item->kelas ?? '') . '|' . ($item->matkul ?? '');
                            });

                            $isWeekend = in_array($hari, ['Sabtu', 'Minggu']);
                          @endphp

                          @if($items->count() > 0)
                            <td class="jadwal-cell">
                              @foreach($items as $item)
                                <div class="jadwal-item">
                                  <div class="jadwal-matkul">{{ $item->matkul }}</div>
                                  <div class="jadwal-dosen">{{ $formatDosen($item) }}</div>
                                  <div class="jadwal-meta">
                                    Kelas {{ $item->kelas }} | {{ $item->jumlah_sks }} SKS
                                  </div>
                                  <div class="jadwal-meta">{{ $item->nama_ruang }}</div>
                                </div>
                              @endforeach
                            </td>
                          @else
                            @if($isWeekend)
                              <td class="weekend-empty">
                                <div class="weekend-label-wrap">Tidak Digunakan</div>
                              </td>
                            @else
                              <td class="empty-cell">-</td>
                            @endif
                          @endif
                        @endforeach
                      </tr>
                    @endif
                  @empty
                    <tr>
                      <td colspan="8" class="jadwal-empty-message">
                        Belum ada data jadwal untuk semester ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      @endforeach
    @endforeach

  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tahunSelect = document.getElementById('search-tahun');
    const sections = document.querySelectorAll('.jadwal-section');

    function tampilkanJadwal() {
      const tahun = tahunSelect.value;

      sections.forEach(function (section) {
        section.style.display = section.dataset.tahun === tahun ? 'block' : 'none';
      });
    }

    tahunSelect.addEventListener('change', tampilkanJadwal);

    if (tahunSelect.options.length > 1) {
      tahunSelect.selectedIndex = 1;
      tampilkanJadwal();
    }
  });
</script>

@endsection
