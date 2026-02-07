@extends('layouts.app')

@section('title',' Tambah Users | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah User</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/manageusers"></i>Manage Users</a></li>
              <li class="breadcrumb-item active">Tambah User</li>
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
            <div class="col-6">
                <div class="card text-choThem">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Form Tambah User</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <form method="post" action="/manageusers">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Nama User" value="{{ old('nama') }}">
                                @error('nama')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input name="username" type="text" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username" value="{{ old('username') }}">
                                @error('username')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input name="email" type="text" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="emailuser@gmai.com" value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email" class="d-block">Aktif</label>
                                <div class="icheck-greenTheme d-inline ml-2">
                                    <input type="radio" name="is_active" checked id="r-aktif1" value="1">
                                    <label for="r-aktif1">
                                        Aktif
                                    </label>
                                </div>
                                <div class="icheck-greenTheme d-inline ml-2">
                                    <input type="radio" name="is_active" id="r-aktif2" value="2">
                                    <label for="r-aktif2">
                                        Non Aktif
                                    </label>
                                </div>
                            </div>
                           <div class="form-group">
                                <label for="email" class="d-block">Status</label>
                                <div class="icheck-greenTheme d-inline ml-2">
                                    <input type="radio" name="role_id" checked id="r-status1" value="1">
                                    <label for="r-status1">Admin</label>
                                </div>
                                <div class="icheck-greenTheme d-inline ml-2">
                                    <input type="radio" name="role_id" id="r-status2" value="2">
                                    <label for="r-status2">Dosen</label>
                                </div>
                                <div class="icheck-greenTheme d-inline ml-2">
                                    <input type="radio" name="role_id" id="r-status3" value="3">
                                    <label for="r-status3">Mahasiswa</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" id="password" name="password">
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="password_confirmation">Repeat Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Repeat Password" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <!-- /.card-body -->

                            
                        </div>
                        <div class="card-footer">
                            <a href="/manageusers" class="btn btn-outline-greenTheme">Kembali</a>
                            <button type="submit" class="btn btn-greenTheme float-right">Tambah User</button>
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