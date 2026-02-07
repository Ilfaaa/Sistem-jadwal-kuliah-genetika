@extends('layouts.app')

@section('title','Edit Matkul | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Mata Kuliah {{ ucwords($matkul->nama_matkul) }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managematkul"></i>Manage Matkul</a></li>
              <li class="breadcrumb-item active">Edit Matkul</li>
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
                            <h3 class="card-title text-whiteTheme">Form Edit Mata Kuliah</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <form method="post" action="/managekuliah/managematkul/{{ $matkul->kode_matkul }}/{{ $tahun_ajaran }}">
                                @method('patch')
                                @csrf
                                <div class="form-group">
                                    <label for="nama_matkul">Nama Mata Kuliah</label>
                                    <input name="nama_matkul" type="text" class="form-control @error('nama_matkul') is-invalid @enderror" id="nama_matkul" placeholder="Nama matkul" value="{{ ucwords($matkul->nama_matkul) }}">
                                    @error('nama_matkul')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                <label>Jumlah SKS</label>
                                <select name="jumlah_sks" class="form-control select2bs4 @error('jumlah_sks') is-invalid @enderror" style="width: 100%;">
                                    <option @if($matkul->sks == '2') selected @endif value="2">2</option>
                                    <option @if($matkul->sks == '3') selected @endif value="3">3</option>
                                    <option @if($matkul->sks == '4') selected @endif value="4">4</option>
                                    <option @if($matkul->sks == '5') selected @endif value="5">5</option>
                                </select>
                                @error('jumlah_sks')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Periode Semester</label>
                                <select name="periode_semester" class="form-control select2bs4 @error('periode_semester') is-invalid @enderror" style="width: 100%;">
                                    @foreach($semester as $s):
                                    <option @if($matkul->kode_semester == $s->kode_semester) selected @endif  value="{{ $s->kode_semester }}">{{ $s->nama_semester }}</option>
                                    @endforeach
                                </select>
                                @error('periode_semester')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Perkuliahan Semester</label>
                                <select name="perkuliahan_semester" class="form-control select2bs4 @error('perkuliahan_semester') is-invalid @enderror" style="width: 100%;">
                                    @foreach(range(1,8) as $numb):
                                    <option @if($matkul->perkuliahan_semester == $numb) selected @endif  value="{{ $numb }}">{{ $numb }}</option>
                                    @endforeach
                                </select>
                                @error('perkuliahan_semester')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="/managekuliah/managematkul" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Edit Matkul</button>
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