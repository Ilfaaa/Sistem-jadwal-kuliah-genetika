@extends('layouts.app')

@section('title','Dashboard | Sistem Penjadwalan Kuliah')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-around">
          <div class="col-lg-2 col-6" style="width: 19.499999995%;
          flex: 0 0 19.499%;max-width: 19.499%;">
            <!-- small box -->
            <div class="small-box bg-indigo">
              <div class="inner">
                <h3>{{$countDosen}}</h3>
                <p>DOSEN</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
                @if($user_login->role_id != 2)
              <a href="/managekuliah/managedosen" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
               @endif
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6" style="width: 19.499999995%;
          flex: 0 0 19.499%;max-width: 19.499%;">
            <!-- small box -->
            <div class="small-box bg-lightblue">
              <div class="inner">
                <h3>{{$countMatkul}}</h3>
                <p>MATA KULIAH</p>
              </div>
              <div class="icon">
                <i class="fas fa-book"></i>
              </div>
                @if($user_login->role_id != 2)
              <a href="/managekuliah/managematkul" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a> @endif
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6" style="width: 19.499999995%;
          flex: 0 0 19.499%;max-width: 19.499%;">
            <!-- small box -->
            <div class="small-box bg-maroon">
              <div class="inner">
                <h3>{{ $countRuang }}</h3>

                <p>RUANG</p>
              </div>
              <div class="icon">
                <i class="far fa-square"></i>
              </div>
               @if($user_login->role_id != 2)
              <a href="/manageruang" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
               @endif
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6" style="width: 19.499999995%;
          flex: 0 0 19.499%;max-width: 19.499%;">
            <!-- small box -->
            <div class="small-box bg-teal">
              <div class="inner">
                <h3>{{ $countKelas }}</h3>

                <p>KELAS</p>
              </div>
              <div class="icon">
                <i class="fas fa-square"></i>
              </div>
               @if($user_login->role_id != 2)
              <a href="/managekuliah/managekelas" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
               @endif
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6" style="width: 19.499999995%;
          flex: 0 0 19.499%;max-width: 19.499%;">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $countJadwal }}</h3>
                <p>JADWAL</p>
              </div>
              <div class="icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
                @if($user_login->role_id != 2)
              <a href="/hasiljadwal" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                  @endif
            </div>
          </div>
        </div>
        <!-- /.row -->

        {{-- Row, search tahun ajaran --}}
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="form-group">
                  <label>Tahun Ajaran</label>
                  <select name="search-tahun" id="search-tahun" class="form-control select2bs4" style="width: 100%;">
                    <option value="" id="default-tahun-option">-- Silahkan pilih Tahun Ajaran --</option> 
                    @foreach($tahun_ajaran as $tahun)
                      <option value="{{$tahun->tahun_ajaran}}">{{$tahun->tahun_ajaran}}</option>
                    @endforeach
                  </select>
              </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Main row -->
        
        <div id="jadwal_ganjil_wrap"></div>
        <div id="jadwal_genap_wrap"></div>

          
  </div>
  <!-- /.content-wrapper -->
  @endsection