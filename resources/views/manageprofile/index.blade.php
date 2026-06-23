@extends('layouts.app')

@section('title','My Profile | Sistem Penjadwalan Kuliah')

@section('content')
  
  <!-- Main content -->
  <section class="content d-flex flex-column justify-content-center" style="min-height: 80vh;">
    <div class="container-fluid">

      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
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

      <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
          <div class="card shadow-lg" style="border-radius: 15px; border-top: 3px solid #4A70A9;">
            <div class="card-body bg-white" style="border-radius: 15px;">

              <div class="text-center">
                <img
                  class="profile-user-img img-fluid img-circle shadow-sm" style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #fff;"
                  src="{{ asset('/img/profile/'.$user_login->image) }}"
                  alt="User profile picture"
                >
              </div>

              <h3 class="profile-username text-center text-dark">
                {{ ucwords($user_login->name) }}
              </h3>
              <p class="text-center text-muted mb-3" style="font-size: 14px;">{{ $user_login->email }}</p>

              <p class="text-center text-dark mb-1">
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

              <p class="text-center text-dark mt-n1">
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
