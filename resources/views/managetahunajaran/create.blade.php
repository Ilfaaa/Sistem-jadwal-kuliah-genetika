@extends('layouts.app')

@section('title','Tambah Tahun Ajaran | Sistem Penjadwalan Kuliah')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Tahun Ajaran</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Beranda</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managetahunajaran">Kelola Tahun Ajaran</a>
            </li>
            <li class="breadcrumb-item active">Tambah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Tambah Tahun Ajaran</h3>
            </div>

            <form method="post" action="/managetahunajaran">
              @csrf

              <div class="card-body">

                <div class="form-group">
                  <label for="tahun_ajaran">Tahun Ajaran</label>
                  <select name="tahun_ajaran" id="tahun_ajaran" class="form-control select2bs4 @error('tahun_ajaran') is-invalid @enderror" style="width: 100%;">
                    @php $year = (int) date('Y'); @endphp
                    @for($i = -2; $i < 6; $i++)
                      @php
                        $start = ($year - 1) + $i;
                        $end = $start + 1;
                        $val = $start . '/' . $end;
                      @endphp
                      <option value="{{ $val }}" {{ old('tahun_ajaran') == $val ? 'selected' : '' }}>
                        {{ $val }}
                      </option>
                    @endfor
                  </select>
                  @error('tahun_ajaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

              </div>

              <div class="card-footer">
                <a href="/managetahunajaran" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-greenTheme float-right">
                  <i class="fas fa-plus-circle mr-1"></i>Tambah Tahun Ajaran
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection
