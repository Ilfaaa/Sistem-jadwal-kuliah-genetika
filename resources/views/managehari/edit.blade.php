@extends('layouts.app')

@section('title','Edit Hari | Sistem Penjadwalan Kuliah')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Hari</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managehari">Manage Hari</a>
          </li>
          <li class="breadcrumb-item active">Edit Hari</li>
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

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Edit Hari</h3>
          </div>

          <form method="post" action="/managehari/{{ $hari->kode_hari }}">
            @method('patch')
            @csrf

            <div class="card-body">
              <div class="form-group">
                <label>Kode Hari</label>
                <select name="kode_hari" id="select-kode-hari"
                        class="form-control select2bs4 @error('kode_hari') is-invalid @enderror">

                  @foreach($availableCode as $code)
                    @php
                      $selectedKode = old('kode_hari', $hari->kode_hari);
                    @endphp
                    <option value="{{ $code }}" {{ $selectedKode == $code ? 'selected' : '' }}>
                      {{ $code }}
                    </option>
                  @endforeach

                </select>
                @error('kode_hari')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="nama_hari">Nama Hari</label>
                <input name="nama_hari" type="text"
                       class="form-control @error('nama_hari') is-invalid @enderror"
                       id="nama_hari" placeholder="Nama hari"
                       value="{{ old('nama_hari', $hari->nama_hari) }}">
                @error('nama_hari')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer">
              <a href="/managehari" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Edit Hari</button>
            </div>

          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
