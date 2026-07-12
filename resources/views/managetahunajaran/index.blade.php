@extends('layouts.app')

@section('title','Kelola Tahun Ajaran | Sistem Penjadwalan Kuliah')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Kelola Tahun Ajaran</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Beranda</a>
            </li>
            <li class="breadcrumb-item active">Kelola Tahun Ajaran</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-6">
          @if (session('status'))
            <div class="alert alert-dismissible fade show bg-lime" role="alert">
              {{ session('status') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>

      <a href="/managetahunajaran/create" class="btn btn-outline-greenTheme mb-3">
        <i class="fas fa-plus-circle mr-1"></i>Tambah Tahun Ajaran
      </a>

      <div class="row">
        <div class="col-12">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">
                <i class="fas fa-calendar-alt mr-1"></i> Daftar Tahun Ajaran & Riwayat
              </h3>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap text-center">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Tahun Ajaran</th>
                    <th scope="col">Jumlah Mata Kuliah</th>
                    <th scope="col">Jumlah Kelas</th>
                    <th scope="col">Jadwal Ganjil</th>
                    <th scope="col">Jadwal Genap</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($statistik) == 0)
                    <tr>
                      <td colspan="7" class="text-center text-bold text-danger">
                        Belum ada data tahun ajaran.
                      </td>
                    </tr>
                  @endif

                  @foreach($statistik as $index => $s)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td><strong>{{ $s['tahun_ajaran'] }}</strong></td>
                      <td>{{ $s['jumlah_matkul'] }}</td>
                      <td>{{ $s['jumlah_kelas'] }}</td>
                      <td>
                        @if($s['jadwal_ganjil'] > 0)
                          <span class="badge bg-lime">{{ $s['jadwal_ganjil'] }} jadwal</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        @if($s['jadwal_genap'] > 0)
                          <span class="badge bg-lime">{{ $s['jadwal_genap'] }} jadwal</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>
                        <form action="/managetahunajaran/{{ $s['id'] }}" method="post" class="d-inline">
                          @method('delete')
                          @csrf
                          <button type="submit" class="badge bg-maroon"
                            onclick="return confirm('PERHATIAN: Menghapus tahun ajaran {{ $s['tahun_ajaran'] }} akan menghapus SEMUA data terkait (mata kuliah, kelas, jadwal). Yakin ingin melanjutkan?')">
                            <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection
