@extends('layouts.app')

@section('title',' Tambah Kelas | Sistem Penjadwalan Kuliah')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Kelas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managekuliah"></i>Manage Kuliah</a></li>
              <li class="breadcrumb-item"><a href="/managekelas"></i>Manage Kelas</a></li>
              <li class="breadcrumb-item active">Tambah Kelas</li>
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
                        <h3 class="card-title text-whiteTheme">Form Tambah Kelas</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <form method="post" action="/managekuliah/managekelas">
                            @csrf
                            <div class="form-group">
                                <label>Tahun Ajaran</label>
                                  <select name="tahun_ajaran" id="select-tahun_ajaran" class="form-control select2bs4 @error('tahun_ajaran') is-invalid @enderror">
                                    <option value="" selected class="default-select-tahun_ajaran">-- Pilih Tahun Ajaran --</option>
                                    @foreach($tahun_ajaran as $tahun):
                                    <option value="{{ $tahun->tahun_ajaran }}">{{ $tahun->tahun_ajaran }}</option>
                                    @endforeach
                                  </select>
                                @error('tahun_ajaran')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Program Studi</label>
                                <select name="prodi" disabled id="select-prodi" class="form-control select2bs4 @error('prodi') is-invalid @enderror">
                                    <option value="" class="default-select" id="default-select-prodi">-- Program Studi --</option>
                                    @foreach($prodi as $p):
                                    <option value="{{ $p->kode_prodi }}-{{ $p->nama_prodi }}">{{ ucwords($p->nama_prodi) }}</option>
                                    @endforeach
                                </select>
                                @error('prodi')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Mata Kuliah</label>
                                        <select name="matkul" id="select-matkul" disabled="disabled" class="form-control select2bs4 @error('matkul') is-invalid @enderror">
                                            <option value="" selected class="default-select">-- Mata Kuliah --</option>
                                        </select>
                                        @error('matkul')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>Dosen Pengajar</label>
                                            <select name="dosen_pengajar" id="select-dosen" disabled="disabled" class="form-control select2bs4 @error('dosen_pengajar') is-invalid @enderror">
                                                <option value="" selected class="default-select">-- Dosen Pengajar --</option>
                                            </select>
                                        @error('dosen_pengajar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-group">
                                <label>Kelas</label>
                                <select name="kelas" disabled id="select-kelas" class="form-control select2bs4 @error('kelas') is-invalid @enderror" style="width: 100%;">
                                    <option value="" selected class="default-select-kelas">-- Kelas --</option>
                                </select>
                                @error('kelas')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <select name="kapasitas_kelas" class="form-control select2bs4 @error('kapasitas_kelas') is-invalid @enderror" style="width: 100%;">
                                    @foreach(range('1','100') as $n):
                                        <option @if($n == 40) selected @endif value="{{ $n }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                                @error('kapasitas_kelas')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Tambah Kelas</button>
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