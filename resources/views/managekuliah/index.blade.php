@extends('layouts.app')

@section('title','Manage Kuliah | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manage Kuliah</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item active">Manage Kuliah</li>
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
      <div class="col-12 col-md-6">
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

    <a href="/managekuliah/managekelas/create" class="btn btn-outline-greenTheme mb-2">
      <i class="fas fa-plus-circle mr-1"></i>Tambah Data Kelas
    </a>

    {{-- TABEL KULIAH PER TAHUN AJARAN --}}
    @foreach($kuliahByTahun as $kuliah)
      <div class="row">
        <div class="col-12">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">
                Tabel Kuliah <b>Tahun Ajaran {{ $kuliah['tahun_ajaran'] }}</b>
              </h3>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-center">
                <thead>
                  <tr>
                    <th scope="col">Kode Kuliah</th>
                    <th scope="col">Kode Matkul</th>
                    <th scope="col">Kode Dosen</th>
                    <th scope="col">Kode Kelas</th>
                    <th scope="col">Kode Semester</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($kuliah['tabel_kuliah']) == 0)
                    <tr>
                      <td scope="row" colspan="6" class="text-center text-bold text-danger">
                        Data Not Found!
                      </td>
                    </tr>
                  @endif

                  @foreach($kuliah['tabel_kuliah'] as $k)
                    @php
                      $tahunKey = str_replace('/', '-', $kuliah['tahun_ajaran']);
                      $modalId = "detail_kuliah_{$tahunKey}_{$k->kode_kuliah}";
                      $labelId = "detailKuliahLabel_{$tahunKey}_{$k->kode_kuliah}";
                    @endphp

                    <tr>
                      <td scope="row">{{ $k->kode_kuliah }}</td>
                      <td scope="row">{{ $k->kode_matkul }}</td>
                      <td scope="row">{{ $k->kode_dosen }}</td>
                      <td scope="row">{{ $k->kode_kelas }}</td>
                      <td scope="row">{{ $k->kode_semester }}</td>
                      <td scope="row">
                        <button type="button"
                                class="badge bg-warning"
                                data-toggle="modal"
                                data-target="#{{ $modalId }}">
                          <i class="fas fa-search"></i>&nbsp;detail
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    @endforeach

    {{-- MODAL DETAIL (dipisah supaya tidak bikin tabel berat) --}}
    @foreach($detailKuliahByTahun as $kuliah)
      @if(count($kuliah['tabel_kuliah']) != 0)
        @foreach($kuliah['tabel_kuliah'] as $k)
          @php
            $tahunKey = str_replace('/', '-', $kuliah['tahun_ajaran']);

            // Ambil 1 record detail secara aman (tidak mengandalkan index 0)
            if ($k instanceof \Illuminate\Support\Collection) {
              $first = $k->first();
            } elseif (is_array($k)) {
              // kalau $k ternyata sudah berupa 1 record assoc, pakai langsung
              $first = isset($k['kode_kuliah'])
                ? $k
                : ($k[0] ?? (count($k) ? reset($k) : null));
            } else {
              // fallback jika object/tipe lain
              $first = $k;
            }

            $kodeKuliah = data_get($first, 'kode_kuliah');
          @endphp

          @if(empty($kodeKuliah))
            @continue
          @endif

          @php
            $modalId = "detail_kuliah_{$tahunKey}_{$kodeKuliah}";
            $labelId = "detailKuliahLabel_{$tahunKey}_{$kodeKuliah}";
          @endphp

          <div class="modal fade"
               id="{{ $modalId }}"
               tabindex="-1"
               aria-labelledby="{{ $labelId }}"
               aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">

                <div class="modal-header bg-greenTheme text-whiteTheme">
                  <h5 class="modal-title" id="{{ $labelId }}">Detail Kuliah</h5>
                  <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">
                  <div class="form-group">
                    <label for="kode_kuliah_{{ $modalId }}">Kode Kuliah</label>
                    <input type="text" disabled class="form-control"
                           id="kode_kuliah_{{ $modalId }}"
                           value="{{ data_get($first, 'kode_kuliah', '') }}">
                  </div>

                  <div class="form-group">
                    <label for="matkul_{{ $modalId }}">Mata Kuliah</label>
                    <input type="text" disabled class="form-control"
                           id="matkul_{{ $modalId }}"
                           value="{{ ucwords(data_get($first, 'matkul', '')) }}">
                  </div>

                  <div class="form-group">
                    <label for="kelas_{{ $modalId }}">Kelas</label>
                    <input type="text" disabled class="form-control"
                           id="kelas_{{ $modalId }}"
                           value="{{ data_get($first, 'kelas', '') }}">
                  </div>

                  <div class="form-group">
                    <label for="dosen_{{ $modalId }}">Dosen Pengajar</label>
                    <input type="text" disabled class="form-control"
                           id="dosen_{{ $modalId }}"
                           value="{{ ucwords(data_get($first, 'dosen', '')) }}">
                  </div>

                  <div class="form-group">
                    <label for="prodi_{{ $modalId }}">Program Studi</label>
                    <input type="text" disabled class="form-control"
                           id="prodi_{{ $modalId }}"
                           value="{{ ucwords(data_get($first, 'prodi', '')) }}">
                  </div>

                  <div class="form-group">
                    <label for="semester_{{ $modalId }}">Semester</label>
                    <input type="text" disabled class="form-control"
                           id="semester_{{ $modalId }}"
                           value="{{ ucwords(data_get($first, 'semester', '')) }}">
                  </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.08);">
                  <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Kembali</button>
                </div>

              </div>
            </div>
          </div>
        @endforeach
      @endif
    @endforeach

  </div><!-- /.container-fluid -->
</section><!-- /.content -->
@endsection
