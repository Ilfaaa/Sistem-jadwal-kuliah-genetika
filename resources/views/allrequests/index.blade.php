@extends('layouts.app')

@section('title','All Requests | Sistem Penjadwalan Kuliah')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">All Requests</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a></li>
              <li class="breadcrumb-item active">All Requests</li>
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

        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <h3 class="card-title text-chocoTheme">Tabel Requests</h3>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="kuliah">
                    <!-- Post -->
                    <div class="post">

                      @if(count($request_kuliah) == 0 && count($request_ruang) == 0 && count($request_waktu) == 0)
                        <p class="text-center text-danger">Request Not Found \('-')/</p>
                      @endif
                      
                      @foreach($request_kuliah as $req_kuliah) 
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{ asset('/img/profile/'.$req_kuliah->image)}}" alt="user image">
                        <span class="username">
                          <a href="#">{{ ucwords($req_kuliah->name) }}.</a>
                        </span>
                        <span class="description">{{ $req_kuliah->created_at }}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Hi Admin! Request {{ $req_kuliah->request }} {{ $req_kuliah->manage }}.
                      </p>

                      <p>
                        <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                        <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>

                        @if($req_kuliah->manage == 'Mata Kuliah')
                        <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_matkul_{{ $req_kuliah->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                        @endif

                        @if($req_kuliah->manage == 'Dosen')
                        <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_dosen_{{ $req_kuliah->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                        @endif

                        @if($req_kuliah->manage == 'Kelas')
                        <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_kelas_{{ $req_kuliah->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                        @endif

                        @if($req_kuliah->manage == 'Program Studi')
                        <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_prodi_{{ $req_kuliah->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                        @endif

                      </p>
                      <hr/>

                      @if($req_kuliah->manage == 'Mata Kuliah')
                      <!-- Modal Request Matkul -->
                      <div class="modal fade" id="modal_matkul_{{ $req_kuliah->id }}" tabindex="-1" aria-labelledby="modal_matkul_{{ $req_kuliah->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-greenTheme text-whiteTheme">
                              <h5 class="modal-title" id="modal_matkul_{{ $req_kuliah->id }}Label">Detail {{ $req_kuliah->request}} {{ $req_kuliah->manage}}</h5>
                              <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @if($req_kuliah->request == "Tambah Data")
                                <form>
                                  <div class="form-group">
                                    <label for="kode_matkul">kode Matkul</label>
                                    <input type="text" class="form-control" id="kode_matkul" value="{{ $req_kuliah->kode_manage }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="nama_matkul">Nama Matkul</label>
                                    <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="sks">SKS</label>
                                    <input type="text" class="form-control" id="sks" value="{{ $req_kuliah->sks }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="periode_semester">Periode Semester</label>
                                    <input type="text" class="form-control" id="periode_semester" value="{{ ucwords(\App\Models\Semester::where('kode_semester', explode(':',$req_kuliah->kode_semester)[0])->first()->nama_semester) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="perkuliahan_semester">Semester</label>
                                    <input type="text" class="form-control" id="perkuliahan_semester" value="{{ explode(':',$req_kuliah->kode_semester)[1] }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="tahun_ajaran">Tahun Ajaran</label>
                                    <input type="text" class="form-control" id="tahun_ajaran" value="{{ explode(':',$req_kuliah->kode_semester)[2] }}" disabled>
                                  </div>
                                </form>
                              @endif

                              @if($req_kuliah->request == "Ubah Data")
                              @php
                              $kode_semester = explode(':',$req_kuliah->kode_semester)[0];
                              $perkuliahan_semester = explode(':',$req_kuliah->kode_semester)[1];
                              $tahun_ajaran = explode(':',$req_kuliah->kode_semester)[2];
                              $old_data = \App\Models\Matkul::where('kode_matkul', $req_kuliah->kode_manage)->where('tahun_ajaran',$tahun_ajaran)->first();
                              @endphp
                              <p class="h5 text-center text-choTheme">SEBELUM</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_matkul">Nama Matkul</label>
                                  <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($old_data->nama_matkul) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="sks">SKS</label>
                                  <input type="text" class="form-control" id="sks" value="{{ $old_data->sks }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_semester">Periode Semester</label>
                                  <input type="text" class="form-control" id="kode_semester" value="{{ ucwords(\App\Models\Semester::where('kode_semester', $old_data->kode_semester)->first()->nama_semester) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="perkuliahan_semester">Perkuliahan Semester</label>
                                  <input type="text" class="form-control" id="perkuliahan_semester" value="{{ $old_data->perkuliahan_semester }}" disabled>
                                </div>
                              </form>
                              <p class="h5 text-center text-choTheme">SESUDAH</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_matkul">Nama Matkul</label>
                                  <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="sks">SKS</label>
                                  <input type="text" class="form-control" id="sks" value="{{ $req_kuliah->sks }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_semester">Periode Semester</label>
                                  <input type="text" class="form-control" id="kode_semester" value="{{ ucwords(\App\Models\Semester::where('kode_semester', $kode_semester)->first()->nama_semester) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="perkuliahan_semester">Perkuliahan Semester</label>
                                  <input type="text" class="form-control" id="perkuliahan_semester" value="{{ $perkuliahan_semester }}" disabled>
                                </div>
                              </form>
                              @endif

                              @if($req_kuliah->request == "Hapus Data")
                              @php
                              $kode_semester = explode(':',$req_kuliah->kode_semester)[0];
                              $perkuliahan_semester = explode(':',$req_kuliah->kode_semester)[1];
                              $tahun_ajaran = explode(':',$req_kuliah->kode_semester)[2];
                              @endphp
                              <form>
                                <div class="form-group">
                                  <label for="kode_matkul">kode Matkul</label>
                                  <input type="text" class="form-control" id="kode_matkul" value="{{ $req_kuliah->kode_manage }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_matkul">Nama Matkul</label>
                                  <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="sks">SKS</label>
                                  <input type="text" class="form-control" id="sks" value="{{ $req_kuliah->sks }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_semester">Periode Semester</label>
                                  <input type="text" class="form-control" id="kode_semester" value="{{ ucwords(\App\Models\Semester::where('kode_semester', $kode_semester)->first()->nama_semester) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="perkuliahan_semester">Perkuliahan Semester</label>
                                  <input type="text" class="form-control" id="perkuliahan_semester" value="{{ $perkuliahan_semester }}" disabled>
                                </div>
                              </form>
                              @endif

                            </div>
                            <div class="modal-footer" style="border-top: solid 1px #00917C">
                              <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal -->
                      @endif
                      
                      @if($req_kuliah->manage == 'Dosen')
                      <!-- Modal Request Dosen -->
                      <div class="modal fade" id="modal_dosen_{{ $req_kuliah->id }}" tabindex="-1" aria-labelledby="modal_dosen_{{ $req_kuliah->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-greenTheme text-whiteTheme">
                              <h5 class="modal-title" id="modal_dosen_{{ $req_kuliah->id }}Label">Detail {{ $req_kuliah->request}} {{ $req_kuliah->manage}}</h5>
                              <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @if($req_kuliah->request == "Tambah Data")
                                <form>
                                  <div class="form-group">
                                    <label for="kode_dosen">kode Dosen</label>
                                    <input type="text" class="form-control" id="kode_dosen" value="{{ explode('-',$req_kuliah->kode_manage)[0] }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="nidn">NIDN / NIP</label>
                                    <input type="text" class="form-control" id="nidn" value="{{ explode('-',$req_kuliah->kode_manage)[1] }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="nama_dosen">Nama Dosen</label>
                                    <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="nama_prodi">Program Studi</label>
                                    <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($req_kuliah->nama_prodi) }}" disabled>
                                  </div>
                                </form>
                              @endif

                              @if($req_kuliah->request == "Ubah Data")
                              @php
                              $kode_baru = explode('-',$req_kuliah->kode_manage)[0];
                              $kode_lama = explode('-',$req_kuliah->kode_manage)[1];
                              $nidn_code = explode('-',$req_kuliah->kode_manage)[2];
                              $old_data = \App\Models\Dosen::where('kode_dosen', $kode_lama)->first();
                              @endphp
                              <p class="h5 text-center text-choTheme">SEBELUM</p>
                              <form>
                                <div class="form-group">
                                  <label for="kode_dosen">Kode Dosen</label>
                                  <input type="text" class="form-control" id="kode_dosen" value="{{ $old_data->kode_dosen }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nidn">NIDN / NIP</label>
                                  <input type="number" class="form-control" id="nidn" value="{{ $old_data->nidn }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_dosen">Nama Dosen</label>
                                  <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($old_data->nama) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_prodi">Program Studi</label>
                                  <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($old_data->program_studi) }}" disabled>
                                </div>
                              </form>
                              <p class="h5 text-center text-choTheme">SESUDAH</p>
                              <form>
                                <div class="form-group">
                                  <label for="kode_dosen">Kode Dosen</label>
                                  <input type="text" class="form-control" id="kode_dosen" value="{{ $kode_baru }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nidn">NIDN / NIP</label>
                                  <input type="number" class="form-control" id="nidn" value="{{ $nidn_code }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_dosen">Nama Dosen</label>
                                  <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_prodi">Program Studi</label>
                                  <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($req_kuliah->nama_prodi) }}" disabled>
                                </div>
                              </form>
                              @endif

                              @if($req_kuliah->request == "Hapus Data")
                              <form>
                                <div class="form-group">
                                  <label for="kode_dosen">Kode Dosen</label>
                                  <input type="text" class="form-control" id="kode_dosen" value="{{ explode('-',$req_kuliah->kode_manage)[0] }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nidn">NIDN / NIP</label>
                                  <input type="number" class="form-control" id="nidn" value="{{ explode('-',$req_kuliah->kode_manage)[1] }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_dosen">Nama Dosen</label>
                                  <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_prodi">Program Studi</label>
                                  <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($req_kuliah->nama_prodi) }}" disabled>
                                </div>
                              </form>
                              @endif

                            </div>
                            <div class="modal-footer" style="border-top: solid 1px #00917C">
                              <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal -->
                      @endif

                      @if($req_kuliah->manage == 'Kelas')
                      <!-- Modal Request Kelas -->
                      <div class="modal fade" id="modal_kelas_{{ $req_kuliah->id }}" tabindex="-1" aria-labelledby="modal_kelas_{{ $req_kuliah->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-greenTheme text-whiteTheme">
                              <h5 class="modal-title" id="modal_kelas_{{ $req_kuliah->id }}Label">Detail {{ $req_kuliah->request}} {{ $req_kuliah->manage}}</h5>
                              <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @if($req_kuliah->request == "Tambah Data")
                                <form>
                                  <div class="form-group">
                                    <label for="tahun_ajaran">Tahun Ajaran</label>
                                    <input type="text" class="form-control" id="tahun_ajaran" value="{{ $req_kuliah->kode_semester }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="kode_kelas">Kode Kelas</label>
                                    <input type="text" class="form-control" id="kode_kelas" value="{{ $req_kuliah->kode_manage }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="kelas">Kelas</label>
                                    <input type="text" class="form-control" id="kelas" value="{{ $req_kuliah->nama_manage }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="nama_matkul">Nama Matkul</label>
                                    <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($req_kuliah->nama_matkul) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="nama_dosen">Nama Dosen</label>
                                    <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($req_kuliah->nama_dosen) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="kapasitas_kelas">Kapasitas Kelas</label>
                                    <input type="text" class="form-control" id="kapasitas_kelas" value="{{ $req_kuliah->kapasitas_kelas }}" disabled>
                                  </div>
                                </form>
                              @endif

                              @if($req_kuliah->request == "Ubah Data")
                              @php
                              $old_data = \App\Models\Kelas::where('kode_kelas', $req_kuliah->kode_manage)->where('tahun_ajaran', $req_kuliah->kode_semester)->first();
                              @endphp
                              <form>
                                <div class="form-group">
                                  <label for="tahun_ajaran">Tahun_ajaran</label>
                                  <input type="text" class="form-control" id="tahun_ajaran" value="{{ $old_data->tahun_ajaran }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_kelas">kode kelas</label>
                                  <input type="text" class="form-control" id="kode_kelas" value="{{ $old_data->kode_kelas }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kelas">Kelas</label>
                                  <input type="text" class="form-control" id="kelas" value="{{ $old_data->kelas }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_matkul">Nama Matkul</label>
                                  <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($old_data->nama_matkul) }}" disabled>
                                </div>
                                <p class="h5 text-center text-choTheme">SEBELUM</p>
                                <div class="form-group">
                                  <label for="nama_dosen">Nama Dosen</label>
                                  <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($old_data->nama_dosen) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kapasitas_kelas">Kapasitas Kelas</label>
                                  <input type="text" class="form-control" id="kapasitas_kelas" value="{{ $old_data->kapasitas_kelas }}" disabled>
                                </div>
                              </form>
                              <p class="h5 text-center text-choTheme">SESUDAH</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_dosen">Nama Dosen</label>
                                  <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($req_kuliah->nama_dosen) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kapasitas_kelas">Kapasitas Kelas</label>
                                  <input type="text" class="form-control" id="kapasitas_kelas" value="{{ $req_kuliah->kapasitas_kelas }}" disabled>
                                </div>
                              </form>
                              @endif

                              @if($req_kuliah->request == "Hapus Data")
                              <form>
                                <div class="form-group">
                                  <label for="tahun_ajaran">Tahun Ajaran</label>
                                  <input type="text" class="form-control" id="tahun_ajaran" value="{{ $req_kuliah->kode_semester }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_kelas">kode kelas</label>
                                  <input type="text" class="form-control" id="kode_kelas" value="{{ $req_kuliah->kode_manage }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kelas">Kelas</label>
                                  <input type="text" class="form-control" id="kelas" value="{{ $req_kuliah->nama_manage }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_matkul">Nama Matkul</label>
                                  <input type="text" class="form-control" id="nama_matkul" value="{{ ucwords($req_kuliah->nama_matkul) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_dosen">Nama Dosen</label>
                                  <input type="text" class="form-control" id="nama_dosen" value="{{ ucwords($req_kuliah->nama_dosen) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kapasitas_kelas">Kapasitas Kelas</label>
                                  <input type="text" class="form-control" id="kapasitas_kelas" value="{{ $req_kuliah->kapasitas_kelas }}" disabled>
                                </div>
                              </form>
                              @endif

                            </div>
                            <div class="modal-footer" style="border-top: solid 1px #00917C">
                              <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal -->
                      @endif

                      @if($req_kuliah->manage == 'Program Studi')
                      <!-- Modal Request Prodi -->
                      <div class="modal fade" id="modal_prodi_{{ $req_kuliah->id }}" tabindex="-1" aria-labelledby="modal_prodi_{{ $req_kuliah->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-greenTheme text-whiteTheme">
                              <h5 class="modal-title" id="modal_prodi_{{ $req_kuliah->id }}Label">Detail {{ $req_kuliah->request}} {{ $req_kuliah->manage}}</h5>
                              <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @if($req_kuliah->request == "Tambah Data")
                                <form>
                                  <div class="form-group">
                                    <label for="nama_prodi">Nama Prodi</label>
                                    <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="kode_prodi">Kode Prodi</label>
                                    <input type="text" class="form-control" id="kode_prodi" value="{{ $req_kuliah->kode_manage }}" disabled>
                                  </div>
                                </form>
                              @endif

                              @if($req_kuliah->request == "Ubah Data")
                              @php
                              $kode_baru = explode('-', $req_kuliah->kode_manage)[0];
                              $kode_lama = explode('-', $req_kuliah->kode_manage)[1];
                              $old_data = \App\Models\Prodi::where('kode_prodi', $kode_lama)->first();
                              @endphp
                              <p class="h5 text-center text-choTheme">SEBELUM</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_prodi">Nama Prodi</label>
                                  <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($old_data->nama_prodi) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_prodi">Kode Prodi</label>
                                  <input type="text" class="form-control" id="kode_prodi" value="{{ $old_data->kode_prodi }}" disabled>
                                </div>
                              </form>
                              <p class="h5 text-center text-choTheme">SESUDAH</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_prodi">Nama Prodi</label>
                                  <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_prodi">Kode Prodi</label>
                                  <input type="text" class="form-control" id="kode_prodi" value="{{ $kode_baru }}" disabled>
                                </div>
                              </form>
                              @endif

                              @if($req_kuliah->request == "Hapus Data")
                              <form>
                                <div class="form-group">
                                  <label for="nama_prodi">Nama Prodi</label>
                                  <input type="text" class="form-control" id="nama_prodi" value="{{ ucwords($req_kuliah->nama_manage) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="kode_prodi">Kode Prodi</label>
                                  <input type="text" class="form-control" id="kode_prodi" value="{{ $req_kuliah->kode_manage }}" disabled>
                                </div>
                              </form>
                              @endif

                            </div>
                            <div class="modal-footer" style="border-top: solid 1px #00917C">
                              <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                              <a href="/allrequests/kuliah/{{ $req_kuliah->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal -->
                      @endif
                      @endforeach

                      @foreach($request_ruang as $req_ruang) 
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{ asset('/img/profile/'.$req_ruang->image)}}" alt="user image">
                        <span class="username">
                          <a href="#">{{ ucwords($req_ruang->name) }}.</a>
                        </span>
                        <span class="description">{{ $req_ruang->created_at }}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Hi Admin! Request {{ $req_ruang->request }} Ruang.
                      </p>
                      
                      <p>
                        <a href="/allrequests/ruang/{{ $req_ruang->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                        <a href="/allrequests/ruang/{{ $req_ruang->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>

                        <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_ruang_{{ $req_ruang->id }}"><i class="fas fa-info mr-1"></i>Detail</button>

                      </p>

                      <!-- Modal Request ruang -->
                      <div class="modal fade" id="modal_ruang_{{ $req_ruang->id }}" tabindex="-1" aria-labelledby="modal_ruang_{{ $req_ruang->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-greenTheme text-whiteTheme">
                              <h5 class="modal-title" id="modal_ruang_{{ $req_ruang->id }}Label">Detail {{ $req_ruang->request}} Ruang</h5>
                              <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              @if($req_ruang->request == "Tambah Data")
                                <form>
                                  <div class="form-group">
                                    <label for="kode_ruang">kode Ruang</label>
                                    <input type="text" class="form-control" id="kode_ruang" value="{{ $req_ruang->kode_ruang }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="ruang">Nama Ruang</label>
                                    <input type="text" class="form-control" id="ruang" value="{{ ucwords($req_ruang->nama_ruang) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="ruang">Nama Prodi</label>
                                    <input type="text" class="form-control" id="ruang" value="{{ ucwords($req_ruang->nama_prodi) }}" disabled>
                                  </div>
                                </form>
                              @endif

                              @if($req_ruang->request == "Ubah Data")
                              <form>
                                <div class="form-group">
                                  <label for="kode_ruang">kode ruang</label>
                                  <input type="text" class="form-control" id="kode_ruang" value="{{ $req_ruang->kode_ruang }}" disabled>
                                </div>
                                @php
                                $old_data = \App\Models\Ruang::where('kode_ruang', $req_ruang->kode_ruang)->first();
                                @endphp
                              </form>
                              <p class="h5 text-center text-choTheme">SEBELUM</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_ruang">Nama Ruang</label>
                                  <input type="text" class="form-control" id="nama_ruang" value="{{ ucwords($old_data->nama_ruang) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="ruang">Nama Prodi</label>
                                  <input type="text" class="form-control" id="ruang" value="{{ ucwords($old_data->nama_prodi) }}" disabled>
                                </div>
                              </form>
                              <p class="h5 text-center text-choTheme">SESUDAH</p>
                              <form>
                                <div class="form-group">
                                  <label for="nama_ruang">Nama Ruang</label>
                                  <input type="text" class="form-control" id="nama_ruang" value="{{ ucwords($req_ruang->nama_ruang) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="ruang">Nama Prodi</label>
                                  <input type="text" class="form-control" id="ruang" value="{{ ucwords($req_ruang->nama_prodi) }}" disabled>
                                </div>
                              </form>
                              @endif

                              @if($req_ruang->request == "Hapus Data")
                              <form>
                                <div class="form-group">
                                  <label for="kode_ruang">Kode Ruang</label>
                                  <input type="text" class="form-control" id="kode_ruang" value="{{ $req_ruang->kode_ruang }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="nama_ruang">Nama Ruang</label>
                                  <input type="text" class="form-control" id="nama_ruang" value="{{ ucwords($req_ruang->nama_ruang) }}" disabled>
                                </div>
                                <div class="form-group">
                                  <label for="ruang">Nama Prodi</label>
                                  <input type="text" class="form-control" id="ruang" value="{{ ucwords($req_ruang->nama_prodi) }}" disabled>
                                </div>
                              </form>
                              @endif

                            </div>
                            <div class="modal-footer" style="border-top: solid 1px #00917C">
                              <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                              <a href="/allrequests/ruang/{{ $req_ruang->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                              <a href="/allrequests/ruang/{{ $req_ruang->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Modal -->
                      @endforeach

                      @foreach($request_waktu as $req_waktu) 
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{ asset('/img/profile/'.$req_waktu->image)}}" alt="user image">
                          <span class="username">
                            <a href="#">{{ ucwords($req_waktu->name) }}.</a>
                          </span>
                          <span class="description">{{ $req_waktu->created_at }}</span>
                        </div>
                        <!-- /.user-block -->
                        <p>
                          Hi Admin! Request {{ $req_waktu->request }} {{ $req_waktu->manage }}.
                        </p>

                        <p>
                          <a href="/allrequests/waktu/{{ $req_waktu->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                          <a href="/allrequests/waktu/{{ $req_waktu->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
  
                          @if($req_waktu->manage == 'Waktu')
                          <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_waktu_{{ $req_waktu->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                          @endif
  
                          @if($req_waktu->manage == 'Hari')
                          <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_hari_{{ $req_waktu->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                          @endif
  
                          @if($req_waktu->manage == 'Jam')
                          <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal_jam_{{ $req_waktu->id }}"><i class="fas fa-info mr-1"></i>Detail</button>
                          @endif
  
                        </p>
                        <hr/>

                        @if($req_waktu->manage == 'Waktu')
                        <!-- Modal Request waktu -->
                        <div class="modal fade" id="modal_waktu_{{ $req_waktu->id }}" tabindex="-1" aria-labelledby="modal_waktu_{{ $req_waktu->id }}Label" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header bg-greenTheme text-whiteTheme">
                                <h5 class="modal-title" id="modal_waktu_{{ $req_waktu->id }}Label">Detail {{ $req_waktu->request}} {{ $req_waktu->manage}}</h5>
                                <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                @if($req_waktu->request == "Tambah Data")
                                  @php
                                      $hours = explode('-',$req_waktu->jam);
                                  @endphp
                                  <form>
                                    <div class="form-group">
                                      <label for="hari">Hari</label>
                                      <input type="text" class="form-control" id="hari" value="{{ ucwords($req_waktu->nama_hari) }}" disabled>
                                    </div>
                                    <div class="form-group">
                                      <label for="jam">Jam</label>
                                      @foreach($hours as $hour)
                                      <input type="text" class="form-control mb-1" id="jam" value="{{ $hour }}" disabled>
                                      @endforeach
                                    </div>
                                  </form>
                                @endif

                                @if($req_waktu->request == "Hapus Data")
                                <form>
                                  <div class="form-group">
                                    <label for="kode_waktu">Kode waktu</label>
                                    <input type="text" class="form-control" id="kode_waktu" value="{{ $req_waktu->kode_waktu }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="hari">Hari</label>
                                    <input type="text" class="form-control" id="hari" value="{{ ucwords($req_waktu->nama_hari) }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="jam">Jam</label>
                                    <input type="text" class="form-control" id="jam" value="{{ $req_waktu->jam }}" disabled>
                                  </div>
                                </form>
                                @endif

                              </div>
                              <div class="modal-footer" style="border-top: solid 1px #00917C">
                                <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                                <a href="/allrequests/waktu/{{ $req_waktu->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                                <a href="/allrequests/waktu/{{ $req_waktu->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- End Modal -->
                        @endif

                        @if($req_waktu->manage == 'Hari')
                        <!-- Modal Request Hari -->
                        <div class="modal fade" id="modal_hari_{{ $req_waktu->id }}" tabindex="-1" aria-labelledby="modal_hari_{{ $req_waktu->id }}Label" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header bg-greenTheme text-whiteTheme">
                                <h5 class="modal-title" id="modal_hari_{{ $req_waktu->id }}Label">Detail {{ $req_waktu->request}} {{ $req_waktu->manage}}</h5>
                                <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                @if($req_waktu->request == "Tambah Data")
                                  <form>
                                    <div class="form-group">
                                      <label for="kode_hari">Kode Hari</label>
                                      <input type="text" class="form-control" id="kode_hari" value="{{ $req_waktu->kode_hari }}" disabled>
                                    </div>
                                    <div class="form-group">
                                      <label for="hari">Hari</label>
                                      <input type="text" class="form-control" id="hari" value="{{ ucwords($req_waktu->nama_hari) }}" disabled>
                                    </div>
                                  </form>
                                @endif

                                @if($req_waktu->request == "Hapus Data")
                                <form>
                                  <div class="form-group">
                                    <label for="kode_hari">Kode Hari</label>
                                    <input type="text" class="form-control" id="kode_hari" value="{{ $req_waktu->kode_hari }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="hari">Hari</label>
                                    <input type="text" class="form-control" id="hari" value="{{ ucwords($req_waktu->nama_hari) }}" disabled>
                                  </div>
                                </form>
                                @endif

                              </div>
                              <div class="modal-footer" style="border-top: solid 1px #00917C">
                                <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                                <a href="/allrequests/waktu/{{ $req_waktu->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                                <a href="/allrequests/waktu/{{ $req_waktu->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- End Modal -->
                        @endif

                        @if($req_waktu->manage == 'Jam')
                        <!-- Modal Request jam -->
                        <div class="modal fade" id="modal_jam_{{ $req_waktu->id }}" tabindex="-1" aria-labelledby="modal_jam_{{ $req_waktu->id }}Label" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header bg-greenTheme text-whiteTheme">
                                <h5 class="modal-title" id="modal_jam_{{ $req_waktu->id }}Label">Detail {{ $req_waktu->request}} {{ $req_waktu->manage}}</h5>
                                <button type="button" class="close text-whiteTheme" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                @if($req_waktu->request == "Tambah Data")
                                  <form>
                                    <div class="form-group">
                                      <label for="kode_jam">Kode Jam</label>
                                      <input type="text" class="form-control" id="kode_jam" value="{{ $req_waktu->kode_jam }}" disabled>
                                    </div>
                                    <div class="form-group">
                                      <label for="jam">Jam</label>
                                      <input type="text" class="form-control" id="jam" value="{{ $req_waktu->jam }}" disabled>
                                    </div>
                                  </form>
                                @endif

                                @if($req_waktu->request == "Ubah Data")
                                @php
                                $kode_baru = explode('-', $req_waktu->kode_jam)[0];
                                $kode_lama = explode('-', $req_waktu->kode_jam)[1];
                                $old_data = \App\Models\Jam::where('kode_jam', $kode_lama)->first();
                                @endphp
                                <p class="h5 text-center text-choTheme">SEBELUM</p>
                                <form>
                                  <div class="form-group">
                                    <label for="kode_jam">Kode Jam</label>
                                    <input type="text" class="form-control" id="kode_jam" value="{{ $kode_lama }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="jam">Jam</label>
                                    <input type="text" class="form-control" id="jam" value="{{ $old_data->jam }}" disabled>
                                  </div>
                                </form>
                                <p class="h5 text-center text-choTheme">SESUDAH</p>
                                <form>
                                  <div class="form-group">
                                    <label for="kode_jam">Kode Jam</label>
                                    <input type="text" class="form-control" id="kode_jam" value="{{ $kode_baru }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="jam">Jam</label>
                                    <input type="text" class="form-control" id="jam" value="{{ $req_waktu->jam }}" disabled>
                                  </div>
                                </form>
                                @endif

                                @if($req_waktu->request == "Hapus Data")
                                <form>
                                  <div class="form-group">
                                    <label for="kode_jam">Kode Jam</label>
                                    <input type="text" class="form-control" id="kode_jam" value="{{ $req_waktu->kode_jam }}" disabled>
                                  </div>
                                  <div class="form-group">
                                    <label for="jam">Jam</label>
                                    <input type="text" class="form-control" id="jam" value="{{ $req_waktu->jam }}" disabled>
                                  </div>
                                </form>
                                @endif

                              </div>
                              <div class="modal-footer" style="border-top: solid 1px #00917C">
                                <button type="button" class="btn btn-outline-greenTheme mr-auto" data-dismiss="modal">Kembali</button>
                                <a href="/allrequests/waktu/{{ $req_waktu->id }}/reject" class="btn btn-danger mr-1"><i class="fas fa-ban mr-1"></i>Tolak</a>
                                <a href="/allrequests/waktu/{{ $req_waktu->id }}/accept" class="btn btn-primary mr-1"><i class="fas fa-check mr-1"></i>Terima</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- End Modal -->
                        @endif
                      @endforeach
                    </div>
                  </div>

                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Main row -->
        <div class="row">
          
  </div>
  <!-- /.content-wrapper -->
  @endsection