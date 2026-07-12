@extends('layouts.app')

@section('title','Kelola Mata Kuliah | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Kelola Mata Kuliah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Beranda</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah">Kelola Perkuliahan</a>
            </li>
            <li class="breadcrumb-item active">Kelola Mata Kuliah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
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

      @if(session('user_login') && ($user_login->role_id == 1 || $user_login->role_id == 2))
      <a href="/managekuliah/managematkul/create" class="btn btn-outline-greenTheme mb-2">
        <i class="fas fa-plus-circle mr-1"></i>Tambah Data Mata Kuliah
      </a>
      @endif

      @foreach($matkulByTahun as $matkulIdx => $matkul)
        <div class="row">
          <div class="col-12">
            <div class="card text-choTheme">
              <div class="card-header bg-greenTheme d-flex align-items-center">
                <h3 class="card-title text-whiteTheme mr-auto">
                  Tabel Mata Kuliah Tahun Ajaran <b>{{ $matkul[0] }}</b>
                </h3>
                <div class="btn-group btn-group-sm ml-2 semester-filter-group" data-target="table-{{ $matkulIdx }}">
                  <button type="button" class="btn btn-light semester-filter-btn active" data-filter="semua" style="font-size: 0.75rem;">
                    <i class="fas fa-list mr-1"></i>Semua
                  </button>
                  <button type="button" class="btn btn-outline-light semester-filter-btn" data-filter="ganjil" style="font-size: 0.75rem;">
                    <i class="fas fa-sort-numeric-down mr-1"></i>Ganjil
                  </button>
                  <button type="button" class="btn btn-outline-light semester-filter-btn" data-filter="genap" style="font-size: 0.75rem;">
                    <i class="fas fa-sort-numeric-up mr-1"></i>Genap
                  </button>
                </div>
              </div>

              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap text-center" id="table-{{ $matkulIdx }}">
                  <thead>
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Kode Matkul</th>
                      <th scope="col" class="sortable-th" style="cursor: pointer;">Nama Matkul</th>
                      <th scope="col" class="sortable-th" style="cursor: pointer;">Tipe</th>
                      <th scope="col" class="sortable-th" style="cursor: pointer;">SKS</th>
                      <th scope="col" class="sortable-th" style="cursor: pointer;">Jenis</th>
                      <th scope="col">Dosen Pengampu</th>
                      <th scope="col" class="sortable-th" style="cursor: pointer;">Periode Semester</th>
                      <th scope="col" class="sortable-th" style="cursor: pointer;">Semester</th>
                      @if(session('user_login') && ($user_login->role_id == 1 || $user_login->role_id == 2))
                      <th scope="col">Aksi</th>
                      @endif
                    </tr>
                  </thead>

                  <tbody>
                    @if(count($matkul[1]) == 0)
                      <tr>
                        <td scope="row" colspan="10" class="text-center text-bold text-danger">
                          Data tidak ditemukan!
                        </td>
                      </tr>
                    @endif

                    @foreach($matkul[1] as $mk)
                      @php
                        // URL safe: 2024/2025 -> 2024-2025
                        $tahunUrl = str_replace('/', '-', $mk->tahun_ajaran);

                        // Gunakan preloaded map (tanpa N+1 query)
                        $semesterObj = $semesterByKode[$mk->kode_semester] ?? null;
                        $namaSemester = $semesterObj->nama_semester ?? null;
                        $semesterType = 'unknown';
                        if ($namaSemester) {
                            $semesterType = str_contains(strtolower($namaSemester), 'genap') ? 'genap' : (str_contains(strtolower($namaSemester), 'ganjil') ? 'ganjil' : 'unknown');
                        }

                        // Dosen pengampu
                        $keyPengampu = $mk->kode_matkul . '|' . $mk->tahun_ajaran;
                        $dosenList = $dosenPengampuMap[$keyPengampu] ?? [];
                      @endphp

                      <tr data-semester="{{ $semesterType }}">
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td scope="row">{{ $mk->kode_matkul }}</td>
                        <td scope="row">{{ ucwords($mk->nama_matkul) }}</td>
                        <td scope="row">
                          @if(($mk->tipe_matkul ?? 'wajib') == 'pilihan')
                            <span class="badge bg-teal text-white" style="color: #fff !important;">Pilihan</span>
                          @else
                            <span class="badge bg-purple text-white" style="color: #fff !important;">Wajib</span>
                          @endif
                        </td>
                        <td scope="row">{{ $mk->sks }}</td>
                        <td scope="row">
                          @if(($mk->jenis_matkul ?? 'teori') == 'praktikum')
                            <span class="badge bg-orange text-white" style="color: #fff !important;">Praktikum</span>
                          @else
                            <span class="badge bg-info">Teori</span>
                          @endif
                        </td>
                        <td scope="row" style="white-space: normal; max-width: 200px;">
                          @if(count($dosenList) > 0)
                              @foreach($dosenList as $dosenKode)
                                  <span class="badge bg-info class-dosen-badge" data-kode="{{ trim($dosenKode) }}">
                                      {{ trim($dosenKode) }}
                                  </span>
                              @endforeach
                          @else
                              <span class="text-muted">Belum ditentukan</span>
                          @endif
                        </td>
                        <td scope="row">
                          @if($semesterType === 'ganjil')
                            <span class="badge" style="background-color: #3c8dbc; color: #fff !important;">{{ ucwords($namaSemester) }}</span>
                          @elseif($semesterType === 'genap')
                            <span class="badge" style="background-color: #00a65a; color: #fff !important;">{{ ucwords($namaSemester) }}</span>
                          @else
                            <span class="text-muted">{{ ucwords($namaSemester ?? '-') }}</span>
                          @endif
                        </td>
                        <td scope="row">{{ $mk->perkuliahan_semester }}</td>
                        @if(session('user_login') && ($user_login->role_id == 1 || $user_login->role_id == 2))
                        <td scope="row">

                          <form action="/managekuliah/managematkul/{{ $mk->kode_matkul }}/{{ $tahunUrl }}/edit" method="get" class="d-inline">
                            <button type="submit" class="badge badge-editTheme">
                              <i class="fas fa-edit"></i>&nbsp;Ubah
                            </button>
                          </form>

                          <form action="/managekuliah/managematkul/{{ $mk->kode_matkul }}/{{ $tahunUrl }}" method="post" class="d-inline">
                            @method('delete')
                            @csrf
                            <button type="submit" class="badge bg-maroon" onclick="return confirm('Yakin ingin menghapus matkul ini?')">
                              <i class="fas fa-trash-alt"></i>&nbsp;Hapus
                            </button>
                          </form>

                        </td>
                        @endif
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

    </div><!-- /.container-fluid -->
  </section><!-- /.content -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {

  // ========== FILTER SEMESTER GANJIL/GENAP ==========
  $('.semester-filter-btn').click(function() {
    var $btn = $(this);
    var $group = $btn.closest('.semester-filter-group');
    var tableId = $group.data('target');
    var $table = $('#' + tableId);
    var filter = $btn.data('filter');

    // Update active state
    $group.find('.semester-filter-btn').removeClass('active btn-light').addClass('btn-outline-light');
    $btn.removeClass('btn-outline-light').addClass('active btn-light');

    // Filter rows
    var $rows = $table.find('tbody tr[data-semester]');
    var visibleCount = 0;

    $rows.each(function() {
      var rowSemester = $(this).data('semester');
      if (filter === 'semua' || rowSemester === filter) {
        $(this).show();
        visibleCount++;
        $(this).find('td').eq(0).text(visibleCount);
      } else {
        $(this).hide();
      }
    });

    // Tampilkan pesan jika tidak ada data
    var $emptyRow = $table.find('tbody tr.semester-empty-row');
    if (visibleCount === 0) {
      if ($emptyRow.length === 0) {
        var filterLabel = filter === 'ganjil' ? 'Ganjil' : (filter === 'genap' ? 'Genap' : '');
        $table.find('tbody').append(
          '<tr class="semester-empty-row"><td colspan="10" class="text-center text-bold text-muted py-3">' +
          '<i class="fas fa-info-circle mr-1"></i>Tidak ada mata kuliah semester ' + filterLabel + '</td></tr>'
        );
      } else {
        $emptyRow.show();
      }
    } else {
      $emptyRow.remove();
    }
  });

  // ========== SORTABLE TABLE HEADERS ==========
  $('.sortable-th').click(function() {
    var table = $(this).parents('table').eq(0);
    var tbody = table.find('tbody');
    var rows = tbody.find('tr[data-semester]:visible').toArray().filter(function(row) {
      return !$(row).find('td').eq(0).hasClass('text-danger') && $(row).find('td').length > 1;
    });
    var index = $(this).index();
    
    // Toggle sort order direction
    this.asc = !this.asc;
    var asc = this.asc;

    rows.sort(function(a, b) {
      var valA = getCellValue(a, index);
      var valB = getCellValue(b, index);

      if ($.isNumeric(valA) && $.isNumeric(valB)) {
        return parseFloat(valA) - parseFloat(valB);
      }

      return valA.localeCompare(valB, undefined, { numeric: true, sensitivity: 'base' });
    });

    if (!asc) {
      rows = rows.reverse();
    }

    for (var i = 0; i < rows.length; i++) {
      $(rows[i]).find('td').eq(0).text(i + 1);
      tbody.append(rows[i]);
    }
  });

  function getCellValue(row, index) {
    var cell = $(row).children('td').eq(index);
    var badge = cell.find('.badge');
    if (badge.length > 0) {
      return badge.text().trim().toLowerCase();
    }
    return cell.text().trim().toLowerCase();
  }
});
</script>
@endpush
