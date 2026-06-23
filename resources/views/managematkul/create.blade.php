@extends('layouts.app')

@section('title','Tambah Mata Kuliah | Sistem Penjadwalan Kuliah')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Mata Kuliah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Beranda</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah">Kelola Perkuliahan</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/managekuliah/managematkul">Kelola Mata Kuliah</a>
            </li>
            <li class="breadcrumb-item active">Tambah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Tambah Mata Kuliah</h3>
            </div>

            <form method="post" action="/managekuliah/managematkul">
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
                    value="{{ old('nama_matkul') }}"
                  >
                  @error('nama_matkul')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Jumlah SKS</label>
                  <select name="jumlah_sks" class="form-control select2bs4 @error('jumlah_sks') is-invalid @enderror" style="width: 100%;">
                    <option value="" selected>-- Jumlah SKS --</option>
                    <option value="1" {{ old('jumlah_sks') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('jumlah_sks') == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('jumlah_sks') == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('jumlah_sks') == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('jumlah_sks') == 5 ? 'selected' : '' }}>5</option>
                  </select>
                  @error('jumlah_sks')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Jenis Mata Kuliah</label>
                  <select name="jenis_matkul" class="form-control select2bs4 @error('jenis_matkul') is-invalid @enderror" style="width: 100%;">
                    <option value="teori" {{ old('jenis_matkul') == 'teori' ? 'selected' : '' }}>Teori (Ruang Kelas Biasa)</option>
                    <option value="praktikum" {{ old('jenis_matkul') == 'praktikum' ? 'selected' : '' }}>Praktikum (Laboratorium)</option>
                  </select>
                  @error('jenis_matkul')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="text-muted">Praktikum akan otomatis dijadwalkan di ruang Laboratorium.</small>
                </div>

                <div class="form-group">
                  <label>Tipe Mata Kuliah</label>
                  <select name="tipe_matkul" class="form-control select2bs4 @error('tipe_matkul') is-invalid @enderror" style="width: 100%;">
                    <option value="wajib" {{ old('tipe_matkul') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                    <option value="pilihan" {{ old('tipe_matkul') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                  </select>
                  @error('tipe_matkul')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Program Studi</label>
                  <select name="program_studi" class="form-control select2bs4 @error('program_studi') is-invalid @enderror" style="width: 100%;">
                    <option value="" selected>-- Program Studi --</option>
                    @foreach($prodi as $p)
                      <option value="{{ $p->kode_prodi }}" {{ old('program_studi') == $p->kode_prodi ? 'selected' : '' }}>
                        {{ ucwords($p->nama_prodi) }}
                      </option>
                    @endforeach
                  </select>
                  @error('program_studi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Dosen Pengampu</label>
                  <select name="dosen_pengampu[]" class="form-control select2bs4" multiple="multiple" style="width: 100%;">
                    @foreach($allDosen as $d)
                      <option value="{{ $d->kode_dosen }}" {{ in_array($d->kode_dosen, old('dosen_pengampu', [])) ? 'selected' : '' }}>
                        {{ $d->kode_dosen }} - {{ ucwords(strtolower($d->nama)) }}
                      </option>
                    @endforeach
                  </select>
                  <small class="text-muted">Pilih dosen yang mengampu mata kuliah ini (opsional, bisa lebih dari satu).</small>
                </div>

                <div class="form-group">
                  <label>Semester</label>
                  <select
                    id="semester_select"
                    name="semester"
                    class="form-control select2bs4 @error('semester') is-invalid @enderror"
                    style="width: 100%;"
                  >
                    <option value="" selected>-- Pilih Semester --</option>
                    @foreach($semester as $s)
                      <option value="{{ $s->kode_semester }}" {{ old('semester') == $s->kode_semester ? 'selected' : '' }}>
                        {{ ucwords($s->nama_semester) }}
                      </option>
                    @endforeach
                  </select>
                  @error('semester')
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
                    disabled
                  >
                    <option value="" selected>-- Pilih Semester Terlebih Dahulu --</option>
                  </select>
                  @error('perkuliahan_semester')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Tahun Ajaran</label>
                  <select name="tahun_ajaran" class="form-control select2bs4 @error('tahun_ajaran') is-invalid @enderror" style="width: 100%;">
                    @php $year = (int) date('Y'); @endphp
                    @for($i = 0; $i < 5; $i++)
                      @php
                        $start = ($year - 1) + $i;
                        $end = $start + 1;
                        $val = $start . '/' . $end;
                      @endphp
                      <option value="{{ $val }}" {{ old('tahun_ajaran') == $val ? 'selected' : '' }}>
                        {{ $val }}
                      </option>
                    @endfor
                  </select>
                  @error('tahun_ajaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

              </div>

              <div class="card-footer">
                <a href="/managekuliah/managematkul" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-greenTheme float-right">Tambah Mata Kuliah</button>
              </div>

            </form>
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection

@push('scripts')
<script>
  // Pastikan jalan setelah jQuery + Select2 siap
  $(function () {
    const $semester = $('#semester_select');
    const $perkuliahan = $('#perkuliahan_semester_select');
    const oldPerkuliahan = @json(old('perkuliahan_semester'));

    function getParity() {
      const val = $semester.val();
      if (!val) return '';

      // Ambil teks dari option yang sedang dipilih
      const text = ($semester.find('option:selected').text() || '').toLowerCase().trim();
      if (text.includes('genap')) return 'even';
      if (text.includes('ganjil')) return 'odd';
      return '';
    }

    function setDisabled(state) {
      $perkuliahan.prop('disabled', state);
      $perkuliahan.trigger('change'); // refresh Select2 UI
    }

    function buildOptions(parity) {
      $perkuliahan.empty();

      if (!parity) {
        $perkuliahan.append('<option value="" selected>-- Pilih Semester Terlebih Dahulu --</option>');
        setDisabled(true);
        return;
      }

      setDisabled(false);
      $perkuliahan.append('<option value="" selected>-- Pilih Perkuliahan Semester --</option>');

      for (let n = 1; n <= 8; n++) {
        const isOdd = (n % 2 === 1);
        if (parity === 'odd' && !isOdd) continue;
        if (parity === 'even' && isOdd) continue;

        const selected = (String(oldPerkuliahan) === String(n)) ? 'selected' : '';
        $perkuliahan.append(`<option value="${n}" ${selected}>${n}</option>`);
      }

      $perkuliahan.trigger('change'); // refresh Select2 UI
    }

    // Listener: select2 event + change fallback
    $semester.on('select2:select change', function () {
      buildOptions(getParity());
    });

    // Init saat load (kalau semester sudah kepilih)
    buildOptions(getParity());
  });
</script>
@endpush