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
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managekelas">Manage Kelas</a>
          </li>
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
      <div class="col-12 col-md-6">
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

    @php
      $selectedDosen = old('dosen_pengajar', $kelas->nama_dosen);
      $selectedKapasitas = old('kapasitas_kelas', $kelas->kapasitas_kelas);
    @endphp

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Edit Kelas</h3>
          </div>

          <form method="post" action="/managekelas/{{ $kelas->kode_kelas }}">
            @method('patch')
            @csrf

            <div class="card-body table-responsive">
              <div class="form-group">
                <label>Dosen Pengajar</label>
                <select name="dosen_pengajar"
                        class="form-control select2bs4 @error('dosen_pengajar') is-invalid @enderror">
                  @foreach($allDosenByProdi as $dosen)
                    <option value="{{ $dosen->nama }}" {{ $selectedDosen == $dosen->nama ? 'selected' : '' }}>
                      {{ ucwords($dosen->nama) }}
                    </option>
                  @endforeach
                </select>
                @error('dosen_pengajar')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Kapasitas</label>
                <select name="kapasitas_kelas"
                        class="form-control select2bs4 @error('kapasitas_kelas') is-invalid @enderror"
                        style="width: 100%;">
                  @foreach(range(1, 100) as $n)
                    <option value="{{ $n }}" {{ (int)$selectedKapasitas === (int)$n ? 'selected' : '' }}>
                      {{ $n }}
                    </option>
                  @endforeach
                </select>
                @error('kapasitas_kelas')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a href="/managekelas" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Edit Kelas</button>
            </div>

          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
