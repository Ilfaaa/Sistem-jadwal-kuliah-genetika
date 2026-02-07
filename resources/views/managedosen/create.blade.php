@extends('layouts.app')

@section('title',' Tambah Dosen | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Dosen</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managekuliah"></i>Manage Kuliah</a></li>
              <li class="breadcrumb-item"><a href="/managedosen"></i>Manage Dosen</a></li>
              <li class="breadcrumb-item active">Tambah Dosen</li>
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
                        <h3 class="card-title text-whiteTheme">Form Tambah Dosen</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <form method="post" action="/managekuliah/managedosen">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Nama Dosen" value="{{ old('nama') }}">
                                @error('nama')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nidn">NIDN / NIP</label>
                                <input name="nidn" type="number" class="form-control @error('nidn') is-invalid @enderror" id="nidn" placeholder="NIDN / NIP Dosen" value="{{ old('nidn') }}">
                                @error('nidn')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Program Studi</label>
                                <select name="program_studi" class="form-control select2bs4 @error('program_studi') is-invalid @enderror" style="width: 100%;">
                                    <option value="" selected="selected">-- Program Studi --</option>
                                    @foreach($prodi as $p):
                                    <option value="{{ $p->nama_prodi }}">{{ ucwords($p->nama_prodi) }}</option>
                                    @endforeach
                                </select>
                                @error('program_studi')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <a href="/managekuliah/managedosen" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Tambah Dosen</button>
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