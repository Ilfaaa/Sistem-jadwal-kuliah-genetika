@extends('layouts.app')

@section('title','Edit Kelas | Sistem Penjadwalan Kuliah')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Kelas {{ $kelas->kelas }} Matkul {{ ucwords($kelas->nama_matkul) }}</h1>
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
            <a href="/managekuliah/managekelas">Manage Kelas</a>
          </li>
          <li class="breadcrumb-item active">Edit Kelas</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12 col-md-6">
        @if (session('kelas_exist'))
          <div class="alert alert-dismissible fade show bg-maroon" role="alert">
            {{ session('kelas_exist') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

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

    @php
      $selectedKapasitas = old('kapasitas_kelas', $kelas->kapasitas_kelas);
    @endphp

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Edit Kelas</h3>
          </div>

          <form method="post" action="/managekuliah/managekelas/{{ $kelas->kode_kelas }}/{{ $tahun_ajaran }}">
            @method('patch')
            @csrf

            <div class="card-body">
              <div class="alert alert-info mb-4">
                <strong>Catatan:</strong> Dosen pengajar tidak lagi diatur pada menu Manage Kelas.
                Relasi dosen, mata kuliah, dan kelas akan ditentukan otomatis saat proses
                <strong>Generate Jadwal</strong>, lalu hasil finalnya dapat dilihat pada menu
                <strong>Hasil Jadwal</strong>.
              </div>

              <div class="form-group">
                <label>Kode Kelas</label>
                <input type="text" class="form-control" value="{{ $kelas->kode_kelas }}" disabled>
              </div>

              <div class="form-group">
                <label>Mata Kuliah</label>
                <input type="text" class="form-control" value="{{ ucwords($kelas->nama_matkul) }}" disabled>
              </div>

              <div class="form-group">
                <label>Kelas</label>
                <input type="text" class="form-control" value="{{ $kelas->kelas }}" disabled>
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

            <div class="card-footer">
              <a href="/managekuliah/managekelas" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Edit Kelas</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
