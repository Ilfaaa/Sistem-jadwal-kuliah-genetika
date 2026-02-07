@extends('layouts.app')

@section('title','Manage Waktu | Sistem Penjadwalan Kuliah')

@section('content')

    <input type="hidden" name="has_search" class="has_search" value="{{ $request_keyword == "" ? "" : $request_keyword }}">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Waktu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item active">Manage Waktu</li>
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
        
        <a href="/managewaktu/create" class="btn btn-outline-greenTheme mb-2"><i class="fas fa-plus-circle mr-1"></i>Tambah Data waktu</a>
       
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card text-choTheme">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Tabel waktu</h3>

                        <form method="post" action="/managewaktu/keyword">
                            @csrf
                            <div class="card-tools" >
                                <div class="input-group input-group-sm float-right" style="width: 250px;">
                                    <input type="text" name="keyword" class="form-control float-right" placeholder="Kode Waktu / Kode Hari / Kode Jam" value="{{ old('keyword') }}">
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
                                    <th scope="col">Kode waktu</th>
                                    <th scope="col">Hari</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($waktu) == 0)
                                    <tr>
                                        <td scope="row" colspan="4" class="text-center text-bold text-danger">Data Not Found!</td>
                                    </tr>
                                @endif
                                @foreach($waktu as $w)
                                <tr>
                                    <td scope="row">{{ $w->kode_waktu }}</td>
                                    <td scope="row">{{ucwords($w->kode_hari)}}</td>
                                    <td scope="row">{{$w->kode_jam}}</td>
                                    <td scope="row">
                                        <form action="/managewaktu/{{ $w->kode_waktu }}" method="post" class="d-inline">
                                            <!-- gunakan method delete agar tidak bisa di ketikkan mengguanakn method post di -->
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="badge bg-maroon"><i class="fas fa-trash-alt"></i>&nbspdelete</button>
                                        </form>
                                        <button type="button" class="badge bg-warning" data-toggle="modal" data-target="#detailWaktu{{ $w->kode_waktu }}">
                                            <i class="fas fa-search"></i>&nbspdetail
                                        </button>
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

    <!-- Button trigger modal -->

  
  <!-- Modal -->

  @foreach($waktuWithDetail as $w)
  <div class="modal fade" id="detailWaktu{{ $w['kode_waktu'] }}" tabindex="-1" aria-labelledby="detailWaktuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-greenTheme text-whiteTheme">
          <h5 class="modal-title" id="detailWaktuLabel">Detail Waktu</h5>
          <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="form-group">
            <label for="kode_waktu">Kode Waktu</label>
            <input name="kode_waktu" type="text" disabled class="form-control" id="kode_waktu" value="{{ $w['kode_waktu'] }}">
        </div>
        <div class="form-group">
            <label for="hari">Hari</label>
            <input name="hari" type="text" disabled class="form-control" id="hari" value="{{ ucwords($w['hari']) }}">
        </div>
        <div class="form-group">
            <label for="jam">Jam</label>
            <input name="jam" type="text" disabled class="form-control" id="jam" value="{{ $w['jam'] }}">
        </div>
        </div>
        <div class="modal-footer" style="border-top: solid 1px #00917C">
            <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  <!-- /.content-wrapper -->
  @endsection