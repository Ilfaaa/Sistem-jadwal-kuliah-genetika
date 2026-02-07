@extends('layouts.app')

@section('title','Edit Password | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Password</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/myprofile">My Profile</a></li>
              <li class="breadcrumb-item active">Edit Password</li>
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
            <div class="col-4">
                @if (session('status'))
                    {!! Session::get('status') !!} 
                @endif
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card text-choTheme">
                  <div class="card-header bg-greenTheme">
                    <h3 class="card-title text-whiteTheme">Form Edit Password</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="/editpassword" method="post" enctype="multipart/form-data">
                    @method('patch')
                    @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                        @error('current_password')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                        @error('new_password')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation">
                        @error('new_password_confirmation')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-profile float-right">Ubah Password</button>
              </div>
          </div>
      </form>
  </div>

</div>
        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection