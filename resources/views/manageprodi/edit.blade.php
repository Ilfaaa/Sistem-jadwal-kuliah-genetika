@extends('layouts.app')

@section('title','Edit Program Studi | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Program Studi {{ ucwords($prodi->nama_prodi) }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah">Manage Kuliah</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah/manageprodi">Manage Prodi</a>
            </li>
            <li class="breadcrumb-item active">Edit Program Studi</li>
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
            <div class="alert alert-dismissible fade show bg-maroon" role="alert">
              {{ session('status') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Edit Program Studi</h3>
            </div>

            <form method="post" action="/managekuliah/manageprodi/{{ $prodi->id_prodi }}">
              @method('patch')
              @csrf

              <div class="card-body">
                <div class="form-group">
                  <label for="nama_prodi">Nama Program Studi</label>
                  <input
                    name="nama_prodi"
                    type="text"
                    class="form-control @error('nama_prodi') is-invalid @enderror"
                    id="nama_prodi"
                    placeholder="Nama Program Studi"
                    value="{{ old('nama_prodi', ucwords($prodi->nama_prodi)) }}"
                  >
                  @error('nama_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="kode_prodi">Kode Program Studi</label>
                  <input
                    name="kode_prodi"
                    type="text"
                    class="form-control @error('kode_prodi') is-invalid @enderror"
                    id="kode_prodi"
                    placeholder="Contoh: TIF"
                    value="{{ old('kode_prodi', $prodi->kode_prodi) }}"
                    maxlength="3"
                    style="max-width: 120px"
                  >
                  @error('kode_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted d-block mt-1">Maksimal 3 karakter.</small>
                </div>
              </div>

              <div class="card-footer">
                <a href="/managekuliah/manageprodi" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-greenTheme float-right">Simpan Perubahan</button>
              </div>
            </form>

          </div>
        </div>
      </div>

    </div>
  </section>
@endsection
