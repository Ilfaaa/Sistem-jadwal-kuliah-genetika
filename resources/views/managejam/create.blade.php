@extends('layouts.app')

@section('title','Tambah Jam | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tambah Jam</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managewaktu/managejam">Manage Jam</a>
          </li>
          <li class="breadcrumb-item active">Tambah Jam</li>
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
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Tambah Jam</h3>
          </div>

          <form method="post" action="/managewaktu/managejam">
            @csrf

            <div class="card-body">
              <div class="form-group">
                <label for="kode_jam">Kode Jam</label>
                <select name="kode_jam" id="kode_jam"
                        class="form-control select2bs4 @error('kode_jam') is-invalid @enderror">
                  @foreach($availableCode as $code)
                    <option value="{{ $code }}" {{ old('kode_jam') == $code ? 'selected' : '' }}>
                      {{ $code }}
                    </option>
                  @endforeach
                </select>
                @error('kode_jam')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="jam">Jam</label>
                    <select name="jam" id="jam"
                            class="form-control select2bs4 @error('jam') is-invalid @enderror">
                      @for($h = 0; $h <= 23; $h++)
                        @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $hh }}" {{ old('jam') == $hh ? 'selected' : '' }}>
                          {{ $hh }}
                        </option>
                      @endfor
                    </select>
                    @error('jam')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-md-6">
                    <label for="menit">Menit</label>
                    <select name="menit" id="menit"
                            class="form-control select2bs4 @error('menit') is-invalid @enderror">
                      @for($m = 0; $m <= 59; $m++)
                        @php $mm = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $mm }}" {{ old('menit') == $mm ? 'selected' : '' }}>
                          {{ $mm }}
                        </option>
                      @endfor
                    </select>
                    @error('menit')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <a href="/managewaktu/managejam" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Tambah Jam</button>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
