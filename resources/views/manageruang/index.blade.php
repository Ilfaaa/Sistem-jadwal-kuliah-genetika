@extends('layouts.app')

@section('title','Manage Ruang | Sistem Penjadwalan Kuliah')

@section('content')
  <input
    type="hidden"
    name="has_search"
    class="has_search"
    value="{{ $request_keyword == '' ? '' : $request_keyword }}"
  >

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Manage Ruang</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item active">Manage Ruang</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

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

      <a href="/manageruang/create" class="btn btn-outline-greenTheme mb-2">
        <i class="fas fa-plus-circle mr-1"></i>Tambah Data Ruang
      </a>

      <div class="row">
        <div class="col-12 col-md-10">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Tabel Ruang</h3>

              <form method="post" action="/manageruang/keyword">
                @csrf
                <div class="card-tools">
                  <div class="input-group input-group-sm float-right" style="width: 280px;">
                    <input
                      type="text"
                      name="keyword"
                      class="form-control float-right"
                      placeholder="Nama ruang / prodi / kapasitas"
                      value="{{ old('keyword', $request_keyword) }}"
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
              <table class="table table-hover text-nowrap text-center">
                <thead>
                  <tr>
                    <th scope="col">Kode Ruang</th>
                    <th scope="col">Nama Ruang</th>
                    <th scope="col">Kapasitas</th>
                    <th scope="col">Tipe Ruang</th>
                    <th scope="col">Nama Prodi</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>

                <tbody>
                  @if(count($ruang) == 0)
                    <tr>
                      <td scope="row" colspan="6" class="text-center text-bold text-danger">
                        Data Not Found!
                      </td>
                    </tr>
                  @endif

                  @foreach($ruang as $r)
                    <tr>
                      <td scope="row">R.{{ $r->kode_ruang }}</td>
                      <td scope="row">{{ ucwords($r->nama_ruang) }}</td>
                      <td scope="row">
                        {{ $r->kapasitas ?? 0 }} mahasiswa
                      </td>
                      <td scope="row">
                        @if(($r->tipe_ruang ?? 'reguler') == 'laboratorium')
                          <span class="badge bg-orange">Laboratorium</span>
                        @else
                          <span class="badge bg-success">Reguler</span>
                        @endif
                      </td>
                      <td scope="row">{{ ucwords($r->nama_prodi) }}</td>
                      <td scope="row">
                        <form action="/manageruang/{{ $r->kode_ruang }}/edit" method="get" class="d-inline">
                          <button type="submit" class="badge bg-lime">
                            <i class="fas fa-edit"></i>&nbsp;edit
                          </button>
                        </form>

                        <form action="/manageruang/{{ $r->kode_ruang }}" method="post" class="d-inline">
                          @method('delete')
                          @csrf
                          <button
                            type="submit"
                            class="badge bg-maroon"
                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                          >
                            <i class="fas fa-trash-alt"></i>&nbsp;delete
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>
@endsection