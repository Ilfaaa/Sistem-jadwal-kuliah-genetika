@extends('layouts.app')

@section('title','Manage Kuliah | Sistem Penjadwalan Kuliah')

@section('content')
{{-- {{ 
dd($kuliahByTahun) }} --}}
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Kuliah</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item active">Manage Kuliah</li>
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
                <div class="alert alert-dismissible fade show bg-lime" role="alert">
                    {{ session('status')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
        <a href="/managekuliah/managekelas/create" class="btn btn-outline-greenTheme mb-2"><i class="fas fa-plus-circle mr-1"></i>Tambah Data Kelas</a>
        <!-- /.row -->
        @foreach($kuliahByTahun as $kuliah)

        <div class="row">
            <div class="col-12">
                <div class="card text-choTheme">
                    <div class="card-header bg-greenTheme">
                        <h3 class="card-title text-whiteTheme">Tabel Kuliah <b>Tahun Ajaran {{ $kuliah['tahun_ajaran'] }}</b></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Kode Kuliah</th>
                                    <th scope="col">Kode Matkul</th>
                                    <th scope="col">Kode Dosen</th>
                                    <th scope="col">Kode Kelas</th>
                                    <th scope="col">Kode Semester</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($kuliah['tabel_kuliah']) == 0)
                                    <tr>
                                        <td scope="row" colspan="6" class="text-center text-bold text-danger">Data Not Found!</td>
                                    </tr>
                                @endif
                                @foreach($kuliah['tabel_kuliah'] as $k)
                                <tr>
                                    <td scope="row">{{$k->kode_kuliah}}</td>
                                    <td scope="row">{{$k->kode_matkul}}</td>
                                    <td scope="row">{{$k->kode_dosen}}</td>
                                    <td scope="row">{{$k->kode_kelas}}</td>
                                    <td scope="row">{{$k->kode_semester}}</td>
                                    <td scope="row">
                                        <button type="button" class="badge bg-warning" data-toggle="modal" data-target="#detail_kuliah_{{ implode('-',explode('/',$kuliah['tahun_ajaran'])) }}_{{ $k->kode_kuliah }}">
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
        @endforeach
        
        @foreach($detailKuliahByTahun as $kuliah)

        @if(count($kuliah['tabel_kuliah']) != 0)
        @foreach($kuliah['tabel_kuliah'] as $k)
        <div class="modal fade" id="detail_kuliah_{{ implode('-',explode('/',$kuliah['tahun_ajaran'])) }}_{{ $k[0]['kode_kuliah'] }}" tabindex="-1" aria-labelledby="detailKuliahLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-greenTheme text-whiteTheme">
                        <h5 class="modal-title" id="detailKuliahLabel">Detail Kuliah</h5>
                        <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_kuliah">Kode Kuliah</label>
                        <input name="kode_kuliah" type="text" disabled class="form-control" id="kode_kuliah" value="{{ ucwords($k[0]['kode_kuliah']) }}">
                    </div>
                    <div class="form-group">
                        <label for="matkul">Mata Kuliah</label>
                        <input name="matkul" type="text" disabled class="form-control" id="matkul" value="{{ ucwords($k[0]['matkul']) }}">
                    </div>
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input name="kelas" type="text" disabled class="form-control" id="kelas" value="{{ $k[0]['kelas'] }}">
                    </div>
                    <div class="form-group">
                        <label for="dosen">Dosen Pengajar</label>
                        <input name="dosen" type="text" disabled class="form-control" id="dosen" value="{{ ucwords($k[0]['dosen']) }}">
                    </div>
                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <input name="prodi" type="text" disabled class="form-control" id="prodi" value="{{ ucwords($k[0]['prodi']) }}">
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input name="semester" type="text" disabled class="form-control" id="semester" value="{{ ucwords($k[0]['semester']) }}">
                    </div>
                    </div>
                    <div class="modal-footer" style="border-top: solid 1px #00917C">
                        <button type="button" class="btn btn-greenTheme" data-dismiss="modal">Kembali</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        @endforeach
<!-- /.content-wrapper -->
@endsection