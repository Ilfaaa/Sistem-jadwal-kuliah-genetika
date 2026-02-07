@extends('layouts.app')

@section('title','Edit Profile | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/myprofile">My Profile</a></li>
              <li class="breadcrumb-item active">Edit Profile</li>
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
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card text-choTheme">
                      <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Form Edit Profile</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="/editprofile" method="post" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="card-body">
                          <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user_login->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user_login->name }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Picture</label>
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="{{ asset('/img/profile/'. $user_login->image)}}" class="img-thumbnail">
                                </div>
                                <div class="input-group col-sm-8">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file">
                                    <label class="custom-file-label" for="file">{{ $user_login->image }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-profile float-right">Edit Profile</button>
              </div>
          </form>
      </div>

  </div>
</div>
        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection