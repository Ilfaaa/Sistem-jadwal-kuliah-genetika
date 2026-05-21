@extends('layouts.app')

@section('title','Edit Waktu | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Waktu</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managewaktu">Manage Waktu</a>
            </li>
            <li class="breadcrumb-item active">Edit Waktu</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
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
          <div class="card text-choThem">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Edit Waktu</h3>
            </div>

            {{-- GANTI $waktu->kode_waktu sesuai field PK kamu --}}
            <form method="post" action="/managewaktu/{{ $waktu->kode_waktu }}">
              @method('patch')
              @csrf

              <div class="card-body table-responsive">

                <div class="form-group">
                  <label>Hari</label>
                  <select
                    name="hari"
                    id="select-hari"
                    class="form-control select2bs4 @error('hari') is-invalid @enderror"
                  >
                    <option value="" class="default-select">-- Pilih Hari --</option>
                    @foreach($availableDays as $day)
                      <option
                        value="{{ $day->kode_hari }}"
                        {{ ($waktu->kode_hari == $day->kode_hari) ? 'selected' : '' }}
                      >
                        {{ ucwords($day->nama_hari) }}
                      </option>
                    @endforeach
                  </select>

                  @error('hari')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Jam</label>

                  <div class="select2-greenTheme">
                    {{-- 
                      Asumsi:
                      - $selectedJam adalah array value jam yang sudah dipilih (mis: ['J01','J02'] atau ['08:00','09:00'])
                      - $availableJam adalah list jam yang boleh dipilih (collection)
                      Kalau struktur datamu beda, tinggal sesuaikan bagian value & selected
                    --}}
                    <select
                      name="jam[]"
                      id="select-jam"
                      multiple
                      class="form-control select2 @error('jam') is-invalid @enderror"
                      data-placeholder="-- Pilih Jam --"
                      data-dropdown-css-class="select2-greenTheme"
                    >
                      @foreach($availableJam as $j)
                        {{-- GANTI field sesuai tabel jam kamu: contoh $j->kode_jam / $j->jam --}}
                        @php
                          $val = $j->kode_jam ?? $j->jam;
                          $label = $j->jam ?? $j->kode_jam;
                        @endphp
                        <option value="{{ $val }}" {{ in_array($val, $selectedJam ?? []) ? 'selected' : '' }}>
                          {{ $label }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  @error('jam')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

              </div>

              <div class="card-footer">
                <a href="/managewaktu" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-greenTheme float-right">Edit Waktu</button>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection
