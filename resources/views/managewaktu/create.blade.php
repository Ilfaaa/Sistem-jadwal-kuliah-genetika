@extends('layouts.app')

@section('title',' Tambah Waktu | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Waktu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managewaktu"></i>Manage Waktu</a></li>
              <li class="breadcrumb-item active">Tambah Waktu</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card text-choThem">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Form Tambah Waktu</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <form method="post" action="/managewaktu">
                            @csrf
                            
                            <div class="form-group">
                                <label>Hari</label>
                                  <select name="hari" id="select-hari" class="form-control select2bs4 @error('hari') is-invalid @enderror">
                                    <option value="" selected class="default-select">-- Pilih Hari --</option>
                                    @foreach($availableDays as $day):
                                    <option value="{{ $day->kode_hari }}">{{ ucwords($day->nama_hari) }}</option>
                                    @endforeach
                                  </select>
                                @error('hari')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                              <label>Jam</label>
                              <div class="select2-greenTheme">
                              <select name="jam[]" id="select-jam" disabled="disabled" multiple class="form-control select2 @error('jam[]') is-invalid @enderror" data-placeholder="-- Pilih Jam --" data-dropdown-css-class="select2-greenTheme" >

                              </select>
                              </div>
                              @error('jam[]')
                              <div class="invalid-feedback">
                                  {{$message}}
                              </div>
                              @enderror
                          </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <a href="/managewaktu" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Tambah Waktu</button>
                        </div>
                    </form>
                    <!-- /.card -->
                </div>
            </div>

        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection