@extends('layouts.app')

@section('title','My Profile | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">My Profile</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item active">My Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-6">
          @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('status') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card card-teal card-outline">
            <div class="card-body box-profile">

              <div class="text-center">
                <img
                  class="profile-user-img img-fluid img-circle"
                  src="{{ asset('/img/profile/'.$user_login->image) }}"
                  alt="User profile picture"
                >
              </div>

              <h3 class="profile-username text-center text-light">
                {{ ucwords($user_login->name) }}
              </h3>

              <p class="text-center text-light mb-1">
                @if($user_login->role_id == 1)
                  Admin
                @elseif($user_login->role_id == 2)
                  Dosen
                @elseif($user_login->role_id == 3)
                  Mahasiswa
                @else
                  Operator
                @endif
              </p>

              <p class="text-center text-light mt-n1">
                @if(empty($user_login->created_at))
                  Member Since A Long Time Ago.
                @else
                  Member Since {{ explode(' ', $user_login->created_at)[0] }}
                @endif
              </p>

              <div class="text-center mt-4">
                <a href="/editprofile" class="btn btn-profile mr-2">
                  <i class="nav-icon fas fa-user-edit mr-1"></i>Edit Profile
                </a>
                <a href="/editpassword" class="btn btn-profile">
                  <i class="nav-icon fas fa-key mr-1"></i>Ubah Password
                </a>
              </div>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>

    </div>
  </section>
  <!-- /.content -->
@endsection
