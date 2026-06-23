@extends('layouts.app')

@section('title','Dashboard | Sistem Penjadwalan Kuliah')

@section('content')

{{-- CSS khusus untuk box dashboard (biru -> merah, tajam & kontras) --}}
<style>
  /* Urutan: Biru -> Sky -> Mint -> Amber -> Rose (kalem tapi hidup) */
  .small-box.box-1 { background: #2F6BFF !important; color: #fff !important; }
  .small-box.box-2 { background: #2CB7FF !important; color: #fff !important; }
  .small-box.box-3 { background: #2FD39A !important; color: #fff !important; }
  .small-box.box-4 { background: #FFB020 !important; color: #fff !important; }
  .small-box.box-5 { background: #FF4D6D !important; color: #fff !important; }

  /* Pastikan semua teks/icon di dalam box ikut putih */
  .small-box.box-1 *, .small-box.box-2 *, .small-box.box-3 *, .small-box.box-4 *, .small-box.box-5 *{
    color:#fff !important;
  }

  /* Footer "Detail" lebih rapih */
  .small-box.box-1 .small-box-footer,
  .small-box.box-2 .small-box-footer,
  .small-box.box-3 .small-box-footer,
  .small-box.box-4 .small-box-footer,
  .small-box.box-5 .small-box-footer {
    background: rgba(0,0,0,0.14) !important;
  }

  .small-box .icon { opacity: 0.30; }
</style>



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

    @if(session('status'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if($user_login->role_id == 3)
      {{-- Dashboard khusus Mahasiswa: Welcome card + langsung ke jadwal --}}
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body text-center py-5">
              <i class="fas fa-user-graduate fa-3x mb-3" style="color: #2F6BFF;"></i>
              <h3>Selamat Datang, {{ ucwords($user_login->name) }}!</h3>
              <p class="text-muted mb-4">Anda login sebagai <strong>Mahasiswa</strong>. Gunakan menu di samping untuk melihat jadwal kuliah yang tersedia.</p>
              <a href="/hasiljadwal" class="btn btn-primary btn-lg">
                <i class="fas fa-clipboard-list mr-2"></i>Lihat Jadwal Kuliah
              </a>
            </div>
          </div>
        </div>
      </div>
    @else
      {{-- Dashboard untuk Admin dan Dosen --}}
      <!-- Small boxes (Stat box) -->
      <div class="row justify-content-around">
        <div class="col-lg-2 col-6" style="width: 19.499999995%; flex: 0 0 19.499%; max-width: 19.499%;">
          <div class="small-box box-1">
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

        <div class="col-lg-2 col-6" style="width: 19.499999995%; flex: 0 0 19.499%; max-width: 19.499%;">
          <div class="small-box box-2">
            <div class="inner">
              <h3>{{$countMatkul}}</h3>
              <p>MATA KULIAH</p>
            </div>
            <div class="icon">
              <i class="fas fa-book"></i>
            </div>
            @if($user_login->role_id != 2)
              <a href="/managekuliah/managematkul" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            @endif
          </div>
        </div>

        <div class="col-lg-2 col-6" style="width: 19.499999995%; flex: 0 0 19.499%; max-width: 19.499%;">
          <div class="small-box box-3">
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

        <div class="col-lg-2 col-6" style="width: 19.499999995%; flex: 0 0 19.499%; max-width: 19.499%;">
          <div class="small-box box-4">
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

        <div class="col-lg-2 col-6" style="width: 19.499999995%; flex: 0 0 19.499%; max-width: 19.499%;">
          <div class="small-box box-5">
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
    @endif

  </div><!-- /.container-fluid -->
</section><!-- /.content -->

@endsection
