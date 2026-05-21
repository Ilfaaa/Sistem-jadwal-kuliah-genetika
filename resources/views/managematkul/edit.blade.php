@extends('layouts.app')

@section('title','Ubah Mata Kuliah | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Ubah Mata Kuliah {{ ucwords($matkul->nama_matkul) }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Beranda</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah/managematkul">Kelola Mata Kuliah</a>
            </li>
            <li class="breadcrumb-item active">Ubah</li>
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
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Ubah Mata Kuliah</h3>
            </div>

            <form method="post" action="/managekuliah/managematkul/{{ $matkul->kode_matkul }}/{{ $tahun_ajaran }}">
              @method('patch')
              @csrf

              <div class="card-body">

                <div class="form-group">
                  <label for="nama_matkul">Nama Mata Kuliah</label>
                  <input
                    name="nama_matkul"
                    type="text"
                    class="form-control @error('nama_matkul') is-invalid @enderror"
                    id="nama_matkul"
                    placeholder="Nama mata kuliah"
                    value="{{ old('nama_matkul', ucwords($matkul->nama_matkul)) }}"
                  >
                  @error('nama_matkul')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Jumlah SKS</label>
                  <select name="jumlah_sks" class="form-control select2bs4 @error('jumlah_sks') is-invalid @enderror" style="width: 100%;">
                    <option value="1" {{ old('jumlah_sks', $matkul->sks) == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('jumlah_sks', $matkul->sks) == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('jumlah_sks', $matkul->sks) == '3' ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('jumlah_sks', $matkul->sks) == '4' ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('jumlah_sks', $matkul->sks) == '5' ? 'selected' : '' }}>5</option>
                  </select>
                  @error('jumlah_sks')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Jenis Mata Kuliah</label>
                  <select name="jenis_matkul" class="form-control select2bs4 @error('jenis_matkul') is-invalid @enderror" style="width: 100%;">
                    <option value="teori" {{ old('jenis_matkul', $matkul->jenis_matkul ?? 'teori') == 'teori' ? 'selected' : '' }}>Teori (Ruang Kelas Biasa)</option>
                    <option value="praktikum" {{ old('jenis_matkul', $matkul->jenis_matkul ?? 'teori') == 'praktikum' ? 'selected' : '' }}>Praktikum (Laboratorium)</option>
                  </select>
                  @error('jenis_matkul')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted">Praktikum akan otomatis dijadwalkan di ruang Laboratorium.</small>
                </div>

                <div class="form-group">
                  <label>Dosen Pengampu</label>
                  <select name="dosen_pengampu[]" class="form-control select2bs4" multiple="multiple" style="width: 100%;">
                    @foreach($allDosen as $d)
                      <option value="{{ $d->kode_dosen }}"
                        {{ in_array($d->kode_dosen, old('dosen_pengampu', $selectedDosen)) ? 'selected' : '' }}>
                        {{ $d->kode_dosen }} - {{ ucwords(strtolower($d->nama)) }}
                      </option>
                    @endforeach
                  </select>
                  <small class="text-muted">Pilih dosen yang mengampu mata kuliah ini (opsional, bisa lebih dari satu).</small>
                </div>

                <div class="form-group">
                  <label>Periode Semester</label>
                  <select
                    id="periode_semester_select"
                    name="periode_semester"
                    class="form-control select2bs4 @error('periode_semester') is-invalid @enderror"
                    style="width: 100%;"
                  >
                    @foreach($semester as $s)
                      <option value="{{ $s->kode_semester }}"
                        {{ old('periode_semester', $matkul->kode_semester) == $s->kode_semester ? 'selected' : '' }}>
                        {{ $s->nama_semester }}
                      </option>
                    @endforeach
                  </select>
                  @error('periode_semester')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Perkuliahan Semester</label>
                  <select
                    id="perkuliahan_semester_select"
                    name="perkuliahan_semester"
                    class="form-control select2bs4 @error('perkuliahan_semester') is-invalid @enderror"
                    style="width: 100%;"
                  >
                    {{-- Akan diisi via JS agar selalu sesuai ganjil/genap --}}
                  </select>
                  @error('perkuliahan_semester')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

              </div><!-- /.card-body -->

              <div class="card-footer">
                <a href="/managekuliah/managematkul" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-greenTheme float-right">Simpan Perubahan</button>
              </div>

            </form>
          </div><!-- /.card -->
        </div><!-- /.col -->
      </div><!-- /.row -->

    </div><!-- /.container-fluid -->
  </section><!-- /.content -->
@endsection

@push('scripts')
<script>
  $(function () {
    const $periode = $('#periode_semester_select');
    const $perkuliahan = $('#perkuliahan_semester_select');

    // prefer old() jika validasi gagal, kalau tidak ada pakai data matkul
    const selectedPerkuliahan = @json(old('perkuliahan_semester', $matkul->perkuliahan_semester));

    function getParity() {
      const text = ($periode.find('option:selected').text() || '').toLowerCase().trim();
      if (text.includes('genap')) return 'even';
      if (text.includes('ganjil')) return 'odd';
      return '';
    }

    function buildOptions(parity) {
      $perkuliahan.empty();

      // Kalau parity tidak terdeteksi, tampilkan 1..8 agar tidak mengunci user.
      const useFilter = (parity === 'odd' || parity === 'even');

      for (let n = 1; n <= 8; n++) {
        if (useFilter) {
          const isOdd = (n % 2 === 1);
          if (parity === 'odd' && !isOdd) continue;
          if (parity === 'even' && isOdd) continue;
        }

        const sel = (String(selectedPerkuliahan) === String(n)) ? 'selected' : '';
        $perkuliahan.append(`<option value="${n}" ${sel}>${n}</option>`);
      }

      $perkuliahan.trigger('change'); // refresh Select2
    }

    $periode.on('select2:select change', function () {
      buildOptions(getParity());
    });

    // init
    buildOptions(getParity());
  });
</script>
@endpush