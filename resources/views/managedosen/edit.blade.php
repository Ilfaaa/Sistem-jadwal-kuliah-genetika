@extends('layouts.app')

@section('title','Edit Dosen | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Dosen {{ ucwords($dosen->nama) }}</h1>
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
            <a href="/managekuliah/managedosen">Manage Dosen</a>
          </li>
          <li class="breadcrumb-item active">Edit Dosen</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Edit Dosen</h3>
          </div>

          <form method="post" action="/managekuliah/managedosen/{{ $dosen->kode_dosen }}">
            @method('patch')
            @csrf

            <div class="card-body">

              <div class="form-group">
                <label for="kode_dosen">Kode Dosen</label>
                <input name="kode_dosen" type="text"
                       class="form-control @error('kode_dosen') is-invalid @enderror"
                       id="kode_dosen" placeholder="Contoh: PR001"
                       value="{{ old('kode_dosen', $dosen->kode_dosen) }}" style="text-transform: uppercase;">
                @error('kode_dosen')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama">Nama</label>
                <input name="nama" type="text"
                       class="form-control @error('nama') is-invalid @enderror"
                       id="nama" placeholder="Nama Dosen"
                       value="{{ old('nama', $dosen->nama) }}">
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="nidn">NIDN / NIP</label>
                <input name="nidn" type="text"
                       class="form-control @error('nidn') is-invalid @enderror"
                       id="nidn" placeholder="NIDN / NIP Dosen"
                       value="{{ old('nidn', $dosen->nidn) }}">
                @error('nidn')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Program Studi</label>
                <select name="program_studi"
                        class="form-control select2bs4 @error('program_studi') is-invalid @enderror"
                        style="width: 100%;">
                  @foreach($prodi as $p)
                    @php
                      $selectedValue = old('program_studi', $dosen->program_studi);
                    @endphp
                    <option value="{{ $p->nama_prodi }}" {{ $selectedValue == $p->nama_prodi ? 'selected' : '' }}>
                      {{ ucwords($p->nama_prodi) }}
                    </option>
                  @endforeach
                </select>
                @error('program_studi')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="no_whatsapp">No. WhatsApp</label>
                <input name="no_whatsapp" type="text"
                       class="form-control @error('no_whatsapp') is-invalid @enderror"
                       id="no_whatsapp" placeholder="Contoh: 08123456789"
                       value="{{ old('no_whatsapp', $dosen->no_whatsapp) }}">
                @error('no_whatsapp')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer">
              <a href="/managekuliah/managedosen" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Edit Dosen</button>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection