@extends('layouts.app')

@section('title','Pasangkan Dosen | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pasangkan Dosen ke Akun</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/manageusers">Manage Users</a>
          </li>
          <li class="breadcrumb-item active">Pasangkan Dosen</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row justify-content-center">
      <div class="col-12 col-md-6">
        @if (session('status'))
          <div class="alert alert-dismissible fade show alert-warning" role="alert">
            {{ session('status')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">
              <i class="fas fa-link mr-2"></i>Form Pasangkan Dosen
            </h3>
          </div>

          <form method="post" action="/manageusers/{{ $user->id_user }}/assign-dosen">
            @csrf

            <div class="card-body">
              <div class="callout callout-info">
                <h5><i class="fas fa-info-circle mr-1"></i> Informasi Akun</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ ucwords($user->name) }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-0"><strong>Username:</strong> {{ $user->username }}</p>
              </div>

              <div class="form-group">
                <label for="kode_dosen">Pilih Dosen</label>
                <select name="kode_dosen" id="kode_dosen" class="form-control select2bs4 @error('kode_dosen') is-invalid @enderror" style="width: 100%;">
                  <option value="">-- Pilih Dosen --</option>
                  @foreach($availableDosen as $dosen)
                    <option value="{{ $dosen->kode_dosen }}" {{ old('kode_dosen') == $dosen->kode_dosen ? 'selected' : '' }}>
                      {{ $dosen->kode_dosen }} - {{ ucwords(strtolower($dosen->nama)) }}
                    </option>
                  @endforeach
                </select>
                @error('kode_dosen')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              @if(count($availableDosen) == 0)
                <div class="alert alert-warning">
                  <i class="fas fa-exclamation-triangle mr-1"></i>
                  Semua dosen sudah dipasangkan ke akun lain. Tambahkan data dosen baru terlebih dahulu atau lepas pemetaan dari akun lain.
                </div>
              @endif
            </div>

            <div class="card-footer">
              <a href="/manageusers" class="btn btn-outline-greenTheme">Kembali</a>
              @if(count($availableDosen) > 0)
                <button type="submit" class="btn btn-greenTheme float-right">
                  <i class="fas fa-link mr-1"></i>Pasangkan
                </button>
              @endif
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
