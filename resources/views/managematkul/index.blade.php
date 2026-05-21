@extends('layouts.app')

@section('title','Kelola Mata Kuliah | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Kelola Mata Kuliah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Beranda</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah">Kelola Perkuliahan</a>
            </li>
            <li class="breadcrumb-item active">Kelola Mata Kuliah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-6">
          @if (session('status'))
            <div class="alert alert-dismissible fade show bg-lime" role="alert">
              {{ session('status') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>

      <a href="/managekuliah/managematkul/create" class="btn btn-outline-greenTheme mb-2">
        <i class="fas fa-plus-circle mr-1"></i>Tambah Data Mata Kuliah
      </a>

      @foreach($matkulByTahun as $matkul)
        <div class="row">
          <div class="col-12">
            <div class="card text-choTheme">
              <div class="card-header bg-greenTheme">
                <h3 class="card-title text-whiteTheme">
                  Tabel Mata Kuliah Tahun Ajaran <b>{{ $matkul[0] }}</b>
                </h3>
              </div>

              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap text-center">
                  <thead>
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Kode Matkul</th>
                      <th scope="col">Nama Matkul</th>
                      <th scope="col">SKS</th>
                      <th scope="col">Jenis</th>
                      <th scope="col">Dosen Pengampu</th>
                      <th scope="col">Periode Semester</th>
                      <th scope="col">Semester</th>
                      <th scope="col">Aksi</th>
                    </tr>
                  </thead>

                  <tbody>
                    @if(count($matkul[1]) == 0)
                      <tr>
                        <td scope="row" colspan="9" class="text-center text-bold text-danger">
                          Data tidak ditemukan!
                        </td>
                      </tr>
                    @endif

                    @foreach($matkul[1] as $mk)
                      @php
                        // URL safe: 2024/2025 -> 2024-2025
                        $tahunUrl = str_replace('/', '-', $mk->tahun_ajaran);

                        // Aman dari null (lebih bagus kalau data ini di-join dari controller)
                        $namaSemester = optional(\App\Models\Semester::where('kode_semester', $mk->kode_semester)->first())->nama_semester;

                        // Dosen pengampu
                        $keyPengampu = $mk->kode_matkul . '|' . $mk->tahun_ajaran;
                        $dosenList = $dosenPengampuMap[$keyPengampu] ?? [];
                      @endphp

                      <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td scope="row">{{ $mk->kode_matkul }}</td>
                        <td scope="row">{{ ucwords($mk->nama_matkul) }}</td>
                        <td scope="row">{{ $mk->sks }}</td>
                        <td scope="row">
                          @if(($mk->jenis_matkul ?? 'teori') == 'praktikum')
                            <span class="badge bg-orange">Praktikum</span>
                          @else
                            <span class="badge bg-info">Teori</span>
                          @endif
                        </td>
                        <td scope="row" style="white-space: normal; max-width: 200px;">
                          @if(count($dosenList) > 0)
                              <span class="badge bg-info">
                                  {{ implode(', ', $dosenList) }}
                                  </span>
                              @else
                                    <span class="text-muted">Belum ditentukan</span>
                              @endif
                        </td>
                        <td scope="row">{{ ucwords($namaSemester ?? '-') }}</td>
                        <td scope="row">{{ $mk->perkuliahan_semester }}</td>
                        <td scope="row">

                          <form action="/managekuliah/managematkul/{{ $mk->kode_matkul }}/{{ $tahunUrl }}/edit" method="get" class="d-inline">
                            <button type="submit" class="badge bg-lime">
                              <i class="fas fa-edit"></i>&nbsp;ubah
                            </button>
                          </form>

                          <form action="/managekuliah/managematkul/{{ $mk->kode_matkul }}/{{ $tahunUrl }}" method="post" class="d-inline">
                            @method('delete')
                            @csrf
                            <button type="submit" class="badge bg-maroon" onclick="return confirm('Yakin ingin menghapus matkul ini?')">
                              <i class="fas fa-trash-alt"></i>&nbsp;hapus
                            </button>
                          </form>

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

    </div><!-- /.container-fluid -->
  </section><!-- /.content -->
@endsection
