@extends('layouts.app')

@section('title','Manage Users | Sistem Penjadwalan Kuliah')

@section('content')
<input type="hidden" name="has_search" class="has_search" value="{{ $request_keyword == "" ? "" : $request_keyword }}">

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Manage Users</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item active">Manage Users</li>
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
          <div class="alert alert-dismissible fade show bg-lime" role="alert">
            {{ session('status')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
      </div>
    </div>

    <a href="/manageusers/create" class="btn btn-outline-greenTheme mb-2">
      <i class="fas fa-user-plus mr-1"></i>Tambah Data User
    </a>

    <div class="row">
      <div class="col-12">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Tabel User</h3>

            <form method="post" action="/manageusers/keyword">
              @csrf
              <div class="card-tools">
                <div class="input-group input-group-sm float-right" style="width: 250px;">
                  <input type="text" name="keyword" class="form-control float-right"
                         placeholder="Nama/Username/Email/Status" value="{{ old('keyword') }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </form>

          </div>
          <!-- /.card-header -->

          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th scope="col">NO</th>
                  <th scope="col">Nama</th>
                  <th scope="col">Email</th>
                  <th scope="col">Status</th>
                  <th scope="col">Aktif</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @if(count($users) == 0)
                  <tr>
                    <td scope="row" colspan="6" class="text-center text-bold text-danger">
                      Users Not Found!
                    </td>
                  </tr>
                @endif

                @foreach($users as $user)
                  <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td scope="row">{{ ucwords($user->name) }}</td>
                    <td scope="row">{{ $user->email }}</td>
                    <td scope="row">
                      @if($user->role_id == 1)
                        Admin
                      @elseif($user->role_id == 2)
                        Dosen
                      @elseif($user->role_id == 3)
                        Mahasiswa
                      @else
                        Unknown Role
                      @endif
                    </td>
                    <td scope="row">{{ $user->is_active == 1 ? "Aktif" : "Non Aktif" }}</td>
                    <td scope="row">
                      <form action="/manageusers/{{ $user->id_user }}/edit" method="get" class="d-inline">
                        <button type="submit" class="badge badge-editTheme">
                          <i class="fas fa-user-edit"></i>&nbsp;ubah
                        </button>
                      </form>

                      <form action="/manageusers/{{ $user->id_user }}" method="post" class="d-inline">
                        @method('delete')
                        @csrf
                        <button type="submit" class="badge bg-maroon">
                          <i class="fas fa-user-times"></i>&nbsp;delete
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
