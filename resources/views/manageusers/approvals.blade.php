@extends('layouts.app')

@section('title','Persetujuan Akun | Sistem Penjadwalan Kuliah')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Persetujuan Akun Baru</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item active">Persetujuan Akun</li>
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

    <div class="row mt-2">
      <div class="col-12">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Daftar Akun Menunggu Persetujuan</h3>
          </div>
          <!-- /.card-header -->

          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th scope="col">NO</th>
                  <th scope="col">Nama</th>
                  <th scope="col">Email</th>
                  <th scope="col">Role Diminta</th>
                  <th scope="col">Tanggal Daftar</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if(count($pendingUsers) == 0)
                  <tr>
                    <td scope="row" colspan="6" class="text-center text-bold text-success">
                      Tidak ada akun yang menunggu persetujuan.
                    </td>
                  </tr>
                @endif

                @foreach($pendingUsers as $user)
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
                    <td scope="row">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}</td>
                    <td scope="row">
                      <!-- Approve Button -->
                      <form action="/manageusers/approvals/{{ $user->id_user }}/approve" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="badge badge-success" style="border:none;">
                          <i class="fas fa-check"></i>&nbsp;Setujui
                        </button>
                      </form>

                      <!-- Reject Button -->
                      <form action="/manageusers/approvals/{{ $user->id_user }}/reject" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menolak dan menghapus akun ini?');">
                        @csrf
                        <button type="submit" class="badge bg-maroon" style="border:none;">
                          <i class="fas fa-times"></i>&nbsp;Tolak
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
