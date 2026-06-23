@extends('layouts.app')

@section('title','Manage Kelas | Sistem Penjadwalan Kuliah')

@section('content')
<input type="hidden" name="has_search" class="has_search" value="{{ $request_keyword == '' ? '' : $request_keyword }}">

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manage Kelas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managekuliah">Manage Kuliah</a>
          </li>
          <li class="breadcrumb-item active">Manage Kelas</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12 col-md-8">
        @if (session('status'))
          <div class="alert alert-dismissible fade show bg-lime" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-dismissible fade show bg-maroon" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>

    @if(session('user_login') && ($user_login->role_id == 1 || $user_login->role_id == 2))
    <div class="mb-3">
      <a href="/managekuliah/managekelas/create" class="btn btn-outline-greenTheme mb-2">
        <i class="fas fa-plus-circle mr-1"></i>Tambah Data Kelas
      </a>
    </div>
    @endif

    <div class="alert alert-info">
      <strong>Catatan:</strong> Menu Manage Kelas hanya digunakan untuk mengelola data kelas, mata kuliah, rombel, dan kapasitas.
      Dosen pengajar tidak lagi ditentukan di halaman ini. Relasi dosen dengan mata kuliah dan kelas akan ditentukan saat proses
      <strong>Generate Jadwal</strong> dan dapat dilihat pada menu <strong>Hasil Jadwal</strong>.
    </div>

    @foreach($kelasByTahun as $kelas)
      <div class="row">
        <div class="col-12">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">
                Tabel Kelas Tahun Ajaran <b>{{ $kelas[0] }}</b>
              </h3>

              <form method="post" action="/managekuliah/managekelas/keyword">
                @csrf
                <div class="card-tools">
                  <div class="input-group input-group-sm float-right" style="width: 280px;">
                    <input
                      type="text"
                      name="keyword"
                      class="form-control float-right"
                      placeholder="Kode kelas / Mata kuliah / Kelas / Kapasitas"
                      value="{{ old('keyword', $request_keyword ?? '') }}"
                    >
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-center mb-0">
                <thead>
                  <tr>
                    <th scope="col">NO</th>
                    <th scope="col">Kode Kelas</th>
                    <th scope="col">Mata Kuliah</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Kapasitas</th>
                    <th scope="col">Status Dosen</th>
                    @if(session('user_login') && ($user_login->role_id == 1 || $user_login->role_id == 2))
                    <th scope="col">Action</th>
                    @endif
                  </tr>
                </thead>

                <tbody>
                  @if(count($kelas[1]) == 0)
                    <tr>
                      <td scope="row" colspan="7" class="text-center text-bold text-danger">
                        Data Not Found!
                      </td>
                    </tr>
                  @endif

                  @foreach($kelas[1] as $k)
                    @php
                      $tahunUrl = str_replace('/', '-', $k->tahun_ajaran);
                    @endphp

                    <tr>
                      <td scope="row">{{ $loop->iteration }}</td>
                      <td scope="row">{{ $k->kode_kelas }}</td>
                      <td scope="row">{{ ucwords($k->nama_matkul) }}</td>
                      <td scope="row">{{ $k->kelas }}</td>
                      <td scope="row">{{ $k->kapasitas_kelas }}</td>
                      <td scope="row">
                        <span class="badge bg-info">Ditentukan saat generate jadwal</span>
                      </td>
                      @if(session('user_login') && ($user_login->role_id == 1 || $user_login->role_id == 2))
                      <td scope="row">
                        <form action="/managekuliah/managekelas/{{ $k->kode_kelas }}/{{ $tahunUrl }}/edit" method="get" class="d-inline">
                          <button type="submit" class="badge badge-editTheme">
                            <i class="fas fa-edit"></i>&nbsp;ubah
                          </button>
                        </form>

                        <form action="/managekuliah/managekelas/{{ $k->kode_kelas }}/{{ $tahunUrl }}" method="post" class="d-inline">
                          @method('delete')
                          @csrf
                          <button type="submit" class="badge bg-maroon" onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="fas fa-trash-alt"></i>&nbsp;delete
                          </button>
                        </form>
                      </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    @endforeach

  </div>
</section>
@endsection
