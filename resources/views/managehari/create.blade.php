@extends('layouts.app')

@section('title','Tambah Hari | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tambah Hari</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managewaktu/managehari">Manage Hari</a>
          </li>
          <li class="breadcrumb-item active">Tambah Hari</li>
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
            <h3 class="card-title text-whiteTheme">Form Tambah Hari</h3>
          </div>

          <form method="post" action="/managewaktu/managehari">
            @csrf

            <div class="card-body">
              <div class="form-group">
                <label>Nama Hari</label>
                <select name="nama_hari" id="select-kode-hari"
                        class="form-control select2bs4 @error('nama_hari') is-invalid @enderror">
                  @foreach($availableDays as $day)
                    <option value="{{ $day }}" {{ old('nama_hari') == $day ? 'selected' : '' }}>
                      {{ ucwords($day) }}
                    </option>
                  @endforeach
                </select>

                @error('nama_hari')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer">
              <a href="/managewaktu/managehari" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Tambah Hari</button>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
