@extends('layouts.app')

@section('title','Edit Profile | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Profile</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/myprofile">My Profile</a>
            </li>
            <li class="breadcrumb-item active">Edit Profile</li>
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
        <div class="col-12 col-md-8">

          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Edit Profile</h3>
            </div>

            <form action="/editprofile" method="post" enctype="multipart/form-data">
              @method('patch')
              @csrf

              <div class="card-body">

                <div class="form-group">
                  <label for="email">Email address</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="{{ $user_login->email }}"
                    readonly
                  >
                </div>

                <div class="form-group">
                  <label for="name">Full Name</label>
                  <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $user_login->name) }}"
                  >
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="file">Picture</label>

                  <div class="row align-items-center">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                      <img
                        src="{{ asset('/img/profile/' . $user_login->image) }}"
                        class="img-thumbnail"
                        alt="Profile image"
                      >
                    </div>

                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="custom-file">
                          <input
                            type="file"
                            class="custom-file-input @error('file') is-invalid @enderror"
                            id="file"
                            name="file"
                            accept="image/*"
                          >
                          <label class="custom-file-label" for="file">
                            {{ $user_login->image }}
                          </label>
                        </div>
                      </div>

                      @error('file')
                        <div class="text-danger mt-1" style="font-size: 12px; font-style: italic;">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <a href="/myprofile" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-profile float-right">Edit Profile</button>
              </div>

            </form>
          </div>

        </div>
      </div>

    </div>
  </section>
@endsection
