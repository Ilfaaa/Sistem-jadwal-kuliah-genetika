@extends('layouts.app')

@section('title','Edit Jam | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Jam</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managewaktu/managejam">Manage Jam</a>
          </li>
          <li class="breadcrumb-item active">Edit Jam</li>
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
        @if (session('status'))
          <div class="alert alert-dismissible fade show bg-maroon" role="alert">
            {{ session('status')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>

    @php
      // Jam disimpan seperti "HH:MM" atau "HH:MM:SS"
      $parts = explode(':', $jam->jam ?? '00:00');
      $currentHour = str_pad($parts[0] ?? '00', 2, '0', STR_PAD_LEFT);
      $currentMinute = str_pad($parts[1] ?? '00', 2, '0', STR_PAD_LEFT);

      $selectedKodeJam = old('kode_jam', $jam->kode_jam);
      $selectedHour    = old('jam', $currentHour);
      $selectedMinute  = old('menit', $currentMinute);
    @endphp

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Edit Jam</h3>
          </div>

          <form method="post" action="/managewaktu/managejam/{{ $jam->kode_jam }}">
            @method('patch')
            @csrf

            <div class="card-body">
              <div class="form-group">
                <label for="kode_jam">Kode Jam</label>
                <select name="kode_jam" id="kode_jam"
                        class="form-control select2bs4 @error('kode_jam') is-invalid @enderror">
                  @foreach($availableCode as $code)
                    <option value="{{ $code }}" {{ $selectedKodeJam == $code ? 'selected' : '' }}>
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
                        <option value="{{ $hh }}" {{ $selectedHour == $hh ? 'selected' : '' }}>
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
                        <option value="{{ $mm }}" {{ $selectedMinute == $mm ? 'selected' : '' }}>
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
              <button type="submit" class="btn btn-greenTheme float-right">Edit Jam</button>
            </div>

          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
