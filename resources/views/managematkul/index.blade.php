@extends('layouts.app')

@section('title','Manage Matkul | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Mata Kuliah</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item"><a href="/managekuliah"></i>Manage Perkuliahan</a></li>
              <li class="breadcrumb-item active">Manage Matkul</li>
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
     
        <a href="/managekuliah/managematkul/create" class="btn btn-outline-greenTheme mb-2"><i class="fas fa-plus-circle mr-1"></i>Tambah Data Mata Kuliah</a>
      
        <!-- /.row -->
        @foreach($matkulByTahun as $matkul)
        <div class="row">
            <div class="col-12">
                <div class="card text-choTheme">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Tabel Mata Kuliah Tahun Ajaran <b>{{ $matkul[0] }}</b></h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap text-center">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Kode matkul</th>
                                    <th scope="col">Nama Matkul</th>
                                    <th scope="col">SKS</th>
                                    {{-- <th scope="col">Kode Prodi</th> --}}
                                    <th scope="col">Periode Semester</th>
                                    <th scope="col">Semester</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($matkul[1]) == 0)
                                    <tr>
                                        <td scope="row" colspan="7" class="text-center text-bold text-danger">Data Not Found!</td>
                                    </tr>
                                @endif
                                @foreach($matkul[1] as $mk)
                                <tr>
                                    <td scope="row">{{$loop->iteration}}</td>
                                    <td scope="row">{{$mk->kode_matkul}}</td>
                                    <td scope="row">{{ucwords($mk->nama_matkul)}}</td>
                                    <td scope="row">{{$mk->sks}}</td>
                                    <td>{{ ucwords(\App\Models\Semester::where('kode_semester', $mk->kode_semester)->first()->nama_semester)}}</td>
                                    <td scope="row">{{$mk->perkuliahan_semester}}</td>
                                    <td scope="row">
                                        @php 
                                            $tahun_ajaran_temp = explode('/',$mk->tahun_ajaran);
                                            $tahun_ajaran = implode('-',$tahun_ajaran_temp);
                                        @endphp
                                        <form action="/managekuliah/managematkul/{{ $mk->kode_matkul }}/{{ $tahun_ajaran }}/edit" method="get" class="d-inline">
                                            <button type="submit" class="badge bg-lime"><i class="fas fa-edit"></i>&nbspedit</button>
                                        </form>
                                        <form action="/managekuliah/managematkul/{{ $mk->kode_matkul }}/{{ $tahun_ajaran }}" method="post" class="d-inline">
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
        @endforeach

        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection