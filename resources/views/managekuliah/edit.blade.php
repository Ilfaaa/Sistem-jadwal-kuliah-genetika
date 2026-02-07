@extends('layouts.app')

@section('title','Edit Kelas | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Kelas {{ $kelas->kelas }} Matkul {{ ucwords($kelas->nama_matkul) }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managekelas"></i>Manage Kelas</a></li>
              <li class="breadcrumb-item active">Edit Kelas</li>
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
                    @if (session('kelas_exist'))
                    <div class="alert alert-dismissible fade show bg-maroon" role="alert">
                        {{ session('kelas_exist')}}
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
                            <h3 class="card-title text-whiteTheme">Form Edit Kelas</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <form method="post" action="/managekelas/{{ $kelas->kode_kelas }}">
                                @method('patch')
                                @csrf
                                <div class="form-group">
                                    <label>Dosen Pengajar</label>
                                    <select name="dosen_pengajar" class="form-control select2bs4 @error('dosen_pengajar') is-invalid @enderror">
                                        @foreach($allDosenByProdi as $dosen):
                                            <option @if($kelas->nama_dosen == $dosen->nama) selected @endif value="{{ $dosen->nama }}">{{ ucwords($dosen->nama) }}</option>
                                        @endforeach
                                    </select>
                                    @error('dosen_pengajar')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Kapasitas</label>
                                    <select name="kapasitas_kelas" class="form-control select2bs4 @error('kapasitas_kelas') is-invalid @enderror" style="width: 100%;">
                                        @foreach(range('1','100') as $n):
                                            <option @if($kelas->kapasitas_kelas == $n) selected @endif value="{{ $n }}">{{ $n }}</option>
                                        @endforeach
                                    </select>
                                    @error('kapasitas_kelas')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="/managekelas" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Edit kelas</button>
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