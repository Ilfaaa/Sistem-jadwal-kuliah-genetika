@extends('layouts.app')

@section('title','Edit Program Studi | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Program Studi {{ ucwords($prodi->nama_prodi) }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managekuliah"></i>Manage Kuliah</a></li>
              <li class="breadcrumb-item"><a href="/manageprodi"></i>Manage prodi</a></li>
              <li class="breadcrumb-item active">Edit Program Studi</li>
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
                <div class="col-6">
                    <div class="card text-choThem">
                        <div class="card-header bg-greenTheme">
                            <h3 class="card-title text-whiteTheme">Form Edit Program Studi</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <form method="post" action="/managekuliah/manageprodi/{{ $prodi->id_prodi }}">
                                @method('patch')
                                @csrf
                                <div class="form-group">
                                    <label for="nama_prodi">Nama</label>
                                    <input name="nama_prodi" type="text" class="form-control @error('nama_prodi') is-invalid @enderror" id="nama_prodi" placeholder="Nama program Studi" value="{{ ucwords($prodi->nama_prodi) }}">
                                    @error('nama_prodi')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="kode_prodi">Kode Program Studi</label>
                                    <input name="kode_prodi" type="text" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" placeholder="E-K-P" value="{{ $prodi->kode_prodi }}" maxlength="3" style="max-width: 100px">
                                    @error('kode_prodi')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                            </div>
                            <!-- /.card-body -->
                            
                            <div class="card-footer">
                                <a href="/managekuliah/manageprodi" class="btn btn-outline-greenTheme">Kembali</a>
                                <button type="submit" class="btn btn-greenTheme float-right">Edit Program Studi</button>
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