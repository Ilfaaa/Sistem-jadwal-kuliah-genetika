@extends('layouts.app')

@section('title',' Tambah Ruang | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Ruang</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/manageruang"></i>Manage Ruang</a></li>
              <li class="breadcrumb-item active">Tambah Ruang</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
            <div class="col-6">
                <div class="card text-choThem">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Form Tambah Ruang</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <form method="post" action="/manageruang">
                            @csrf
                            <input type="hidden" name="kode_ruang" id="kode_ruang" value="{{ $kodeRuang }}">
                            <div class="form-group">
                                <label for="nama_ruang">Nama Ruang</label>
                                <input name="nama_ruang" type="text" class="form-control @error('nama_ruang') is-invalid @enderror" id="nama_ruang" placeholder="Nama Ruang" value="{{ old('nama_ruang') }}">
                                @error('nama_ruang')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                              <label>Program Studi</label>
                              <select name="nama_prodi" class="form-control select2bs4 @error('nama_prodi') is-invalid @enderror" style="width: 100%;">
                                  <option value="" selected="selected">-- Program Studi --</option>
                                  @foreach($prodi as $p):
                                  <option value="{{ $p->nama_prodi }}">{{ ucwords($p->nama_prodi) }}</option>
                                  @endforeach
                              </select>
                              @error('nama_prodi')
                              <div class="invalid-feedback">
                                  {{$message}}
                              </div>
                              @enderror
                          </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <a href="/manageruang" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Tambah Ruang</button>
                        </div>
                    </form>
                    <!-- /.card -->
                </div>
            </div>

        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection