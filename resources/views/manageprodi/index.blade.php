@extends('layouts.app')

@section('title','Manage Prodi | Sistem Penjadwalan Kuliah')

@section('content')
<input type="hidden" name="has_search" class="has_search" value="{{ $request_keyword == "" ? "" : $request_keyword }}">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Program Studi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managekuliah"></i>Manage Kuliah</a></li>
              <li class="breadcrumb-item active">Manage Prodi</li>
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
            <div class="col-6">
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
        <a href="/managekuliah/manageprodi/create" class="btn btn-outline-greenTheme mb-2"><i class="fas fa-plus-circle mr-1"></i>Tambah Data Program Studi</a>
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card text-choTheme">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Tabel Program Studi</h3>

                        <form method="post" action="/managekuliah/manageprodi/keyword">
                            @csrf
                            <div class="card-tools" >
                                <div class="input-group input-group-sm float-right" style="width: 250px;">
                                    <input type="text" name="keyword" class="form-control float-right" placeholder="Nama Prodi/Kode Prodi" value="{{ old('keyword') }}">
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
                        <table class="table table-hover text-nowrap text-center">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Nama Prodi</th>
                                    <th scope="col">Kode Prodi</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($prodi) == 0)
                                    <tr>
                                        <td scope="row" colspan="5" class="text-center text-bold text-danger">Data Not Found!</td>
                                    </tr>
                                @endif
                                @foreach($prodi as $p)
                                <tr>
                                    <td scope="row">{{$loop->iteration}}</td>
                                    <td scope="row">{{ucwords($p->nama_prodi)}}</td>
                                    <td scope="row">{{ucwords($p->kode_prodi)}}</td>
                                    <td scope="row">
                                        <form action="/managekuliah/manageprodi/{{ $p->id_prodi }}/edit" method="get" class="d-inline">
                                            <button type="submit" class="badge bg-lime"><i class="fas fa-edit"></i>&nbspedit</button>
                                        </form>
                                        <form action="/managekuliah/manageprodi/{{ $p->id_prodi }}" method="post" class="d-inline">
                                            <!-- gunakan method delete agar tidak bisa di ketikkan mengguanakn method post di -->
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="badge bg-maroon"><i class="fas fa-trash-alt"></i>&nbspdelete</button>
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

        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection