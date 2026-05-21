@extends('layouts.app')

@section('title','Edit Ruang | Sistem Penjadwalan Kuliah')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Ruang</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/manageruang">Manage Ruang</a>
            </li>
            <li class="breadcrumb-item active">Edit Ruang</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

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
              <h3 class="card-title text-whiteTheme">Form Edit Ruang</h3>
            </div>

            <form method="post" action="/manageruang/{{ $ruang->kode_ruang }}">
              @method('patch')
              @csrf

              <div class="card-body">
                <div class="form-group">
                  <label for="nama_ruang">Nama Ruang</label>
                  <input
                    name="nama_ruang"
                    type="text"
                    class="form-control @error('nama_ruang') is-invalid @enderror"
                    id="nama_ruang"
                    placeholder="Nama ruang"
                    value="{{ old('nama_ruang', ucwords($ruang->nama_ruang)) }}"
                  >
                  @error('nama_ruang')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="kapasitas">Kapasitas Mahasiswa</label>
                  <input
                    name="kapasitas"
                    type="number"
                    min="1"
                    max="500"
                    class="form-control @error('kapasitas') is-invalid @enderror"
                    id="kapasitas"
                    placeholder="Contoh: 40"
                    value="{{ old('kapasitas', $ruang->kapasitas ?? 0) }}"
                  >
                  @error('kapasitas')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Tipe Ruang</label>
                  <select
                    name="tipe_ruang"
                    class="form-control select2bs4 @error('tipe_ruang') is-invalid @enderror"
                    style="width: 100%;"
                  >
                    <option value="reguler" {{ old('tipe_ruang', $ruang->tipe_ruang ?? 'reguler') == 'reguler' ? 'selected' : '' }}>Reguler (Ruang Kelas Biasa)</option>
                    <option value="laboratorium" {{ old('tipe_ruang', $ruang->tipe_ruang ?? 'reguler') == 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                  </select>
                  @error('tipe_ruang')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Program Studi</label>
                  <select
                    name="nama_prodi"
                    class="form-control select2bs4 @error('nama_prodi') is-invalid @enderror"
                    style="width: 100%;"
                  >
                    @foreach($prodi as $p)
                      <option
                        value="{{ $p->nama_prodi }}"
                        {{ old('nama_prodi', $ruang->nama_prodi) == $p->nama_prodi ? 'selected' : '' }}
                      >
                        {{ ucwords($p->nama_prodi) }}
                      </option>
                    @endforeach
                  </select>
                  @error('nama_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="card-footer">
                <a href="/manageruang" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-greenTheme float-right">Edit Ruang</button>
              </div>
            </form>

          </div>
        </div>
      </div>

    </div>
  </section>
@endsection