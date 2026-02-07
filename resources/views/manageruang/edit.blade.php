@extends('layouts.app')

@section('title','Edit Ruang | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Ruang</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/manageruang"></i>Manage ruang</a></li>
              <li class="breadcrumb-item active">Edit Ruang</li>
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
                <div class="col-6">
                    @if (session('status'))
                    <div class="alert alert-dismissible fade show bg-maroon" role="alert">
                        {{ session('status')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card text-choThem">
                        <div class="card-header bg-greenTheme">
                            <h3 class="card-title text-whiteTheme">Form Edit Ruang</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <form method="post" action="/manageruang/{{ $ruang->kode_ruang }}">
                                @method('patch')
                                @csrf
                                <div class="form-group">
                                    <label for="nama_ruang">Nama Ruang</label>
                                    <input name="nama_ruang" type="text" class="form-control @error('nama_ruang') is-invalid @enderror" id="nama_ruang" placeholder="Nama ruang" value="{{ ucwords($ruang->nama_ruang) }}">
                                    @error('nama_ruang')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Program Studi</label>
                                    <select name="nama_prodi" class="form-control select2bs4 @error('nama_prodi') is-invalid @enderror" style="width: 100%;">
                                        @foreach($prodi as $p):
                                        <option @if($ruang->nama_prodi == $p->nama_prodi) selected @endif value="{{ $p->nama_prodi }}">{{ ucwords($p->nama_prodi) }}</option>
                                        @endforeach
                                    </select>
                                    @error('nama_prodi')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                            </div>
                            <!-- /.card-body -->
                            
                            <div class="card-footer">
                                <a href="/manageruang" class="btn btn-outline-greenTheme">Kembali</a>
                                <button type="submit" class="btn btn-greenTheme float-right">Edit Ruang</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection