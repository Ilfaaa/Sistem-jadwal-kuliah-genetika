@extends('layouts.app')

@section('title','Tambah Kelas | Sistem Penjadwalan Kuliah')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tambah Kelas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">
            <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managekuliah">Manage Kuliah</a>
          </li>
          <li class="breadcrumb-item">
            <a href="/managekuliah/managekelas">Manage Kelas</a>
          </li>
          <li class="breadcrumb-item active">Tambah Kelas</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12 col-md-6">
        @if (session('kelas_exist'))
          <div class="alert alert-dismissible fade show bg-maroon" role="alert">
            {{ session('kelas_exist') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

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

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-choTheme">
          <div class="card-header bg-greenTheme">
            <h3 class="card-title text-whiteTheme">Form Tambah Kelas</h3>
          </div>

          <form method="post" action="/managekuliah/managekelas">
            @csrf

            <div class="card-body">
              <div class="alert alert-info mb-3">
                <strong>Catatan:</strong> Menu ini hanya digunakan untuk membuat data kelas per mata kuliah.
                Dosen pengajar tidak ditentukan di Manage Kelas. Relasi dosen dengan mata kuliah dan kelas akan ditentukan otomatis saat proses <strong>Generate Jadwal</strong>, lalu hasil akhirnya dapat dilihat pada menu <strong>Hasil Jadwal</strong>.
              </div>

              <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran" id="select-tahun_ajaran"
                        class="form-control select2bs4 @error('tahun_ajaran') is-invalid @enderror">
                  <option value="" selected class="default-select-tahun_ajaran">-- Pilih Tahun Ajaran --</option>
                  @foreach($tahun_ajaran as $tahun)
                    <option value="{{ $tahun->tahun_ajaran }}" {{ old('tahun_ajaran') == $tahun->tahun_ajaran ? 'selected' : '' }}>
                      {{ $tahun->tahun_ajaran }}
                    </option>
                  @endforeach
                </select>
                @error('tahun_ajaran')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Program Studi</label>
                <select name="prodi" id="select-prodi" disabled
                        class="form-control select2bs4 @error('prodi') is-invalid @enderror">
                  <option value="" class="default-select" id="default-select-prodi">-- Program Studi --</option>
                  @foreach($prodi as $p)
                    <option value="{{ $p->kode_prodi }}-{{ $p->nama_prodi }}" {{ old('prodi') == $p->kode_prodi . '-' . $p->nama_prodi ? 'selected' : '' }}>
                      {{ ucwords($p->nama_prodi) }}
                    </option>
                  @endforeach
                </select>
                @error('prodi')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Mata Kuliah</label>
                <select name="matkul" id="select-matkul" disabled
                        class="form-control select2bs4 @error('matkul') is-invalid @enderror">
                  <option value="" selected class="default-select">-- Mata Kuliah --</option>
                </select>
                @error('matkul')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" id="select-kelas" disabled
                        class="form-control select2bs4 @error('kelas') is-invalid @enderror"
                        style="width: 100%;">
                  <option value="" selected class="default-select-kelas">-- Kelas --</option>
                </select>
                <small class="form-text text-muted">
                  Pilihan kelas akan otomatis menampilkan kelas yang belum dibuat untuk mata kuliah dan tahun ajaran tersebut.
                </small>
                @error('kelas')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label>Kapasitas</label>
                <select name="kapasitas_kelas"
                        class="form-control select2bs4 @error('kapasitas_kelas') is-invalid @enderror"
                        style="width: 100%;">
                  @foreach(range(1, 100) as $n)
                    <option value="{{ $n }}" {{ old('kapasitas_kelas', 40) == $n ? 'selected' : '' }}>
                      {{ $n }}
                    </option>
                  @endforeach
                </select>
                @error('kapasitas_kelas')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer">
              <a href="{{ url('/managekuliah/managekelas') }}" class="btn btn-outline-greenTheme">Kembali</a>
              <button type="submit" class="btn btn-greenTheme float-right">Tambah Kelas</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tahunSelect = document.getElementById('select-tahun_ajaran');
    const prodiSelect = document.getElementById('select-prodi');
    const matkulSelect = document.getElementById('select-matkul');
    const kelasSelect = document.getElementById('select-kelas');

    const oldProdi = @json(old('prodi'));
    const oldMatkul = @json(old('matkul'));
    const oldKelas = @json(old('kelas'));

    const kelasDefault = ['A', 'B', 'C', 'D', 'E', 'F'];

    function refreshSelect2(el) {
      if (window.jQuery && jQuery.fn.select2 && jQuery(el).hasClass('select2bs4')) {
        jQuery(el).trigger('change.select2');
      }
    }

    function setOptions(select, placeholder, options) {
      select.innerHTML = '';

      const defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.textContent = placeholder;
      defaultOption.selected = true;
      select.appendChild(defaultOption);

      options.forEach(function (option) {
        const opt = document.createElement('option');
        opt.value = option.value;
        opt.textContent = option.text;

        if (option.disabled) {
          opt.disabled = true;
        }

        select.appendChild(opt);
      });

      refreshSelect2(select);
    }

    function resetMatkul() {
      setOptions(matkulSelect, '-- Mata Kuliah --', []);
      matkulSelect.disabled = true;
    }

    function resetKelas() {
      setOptions(kelasSelect, '-- Kelas --', []);
      kelasSelect.disabled = true;
    }

    function enableProdiIfReady() {
      const tahun = tahunSelect.value;
      prodiSelect.disabled = !tahun;

      if (!tahun) {
        prodiSelect.value = '';
        resetMatkul();
        resetKelas();
      }

      refreshSelect2(prodiSelect);
    }

    function loadMatkul(selectedValue = null) {
      const tahun = tahunSelect.value;
      const prodi = prodiSelect.value;

      resetMatkul();
      resetKelas();

      if (!tahun || !prodi) {
        return;
      }

      fetch(`/managekuliah/managekelas/create/action?prodi=${encodeURIComponent(prodi)}&tahun_ajaran=${encodeURIComponent(tahun)}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.json())
        .then(data => {
          const matkulList = data.allMatkul || [];
          const options = matkulList.map(function (m) {
            return {
              value: `${m.kode_matkul}-${m.nama_matkul}`,
              text: `${m.kode_matkul} - ${m.nama_matkul}`
            };
          });

          setOptions(matkulSelect, '-- Mata Kuliah --', options);
          matkulSelect.disabled = options.length === 0;

          if (selectedValue) {
            matkulSelect.value = selectedValue;
          }

          refreshSelect2(matkulSelect);

          if (matkulSelect.value) {
            loadKelas(oldKelas);
          }
        })
        .catch(function () {
          resetMatkul();
          resetKelas();
        });
    }

    function loadKelas(selectedValue = null) {
      const tahun = tahunSelect.value;
      const matkul = matkulSelect.value;

      resetKelas();

      if (!tahun || !matkul) {
        return;
      }

      fetch(`/managekuliah/managekelas/create/action?matkul=${encodeURIComponent(matkul)}&tahun_ajaran=${encodeURIComponent(tahun)}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.json())
        .then(data => {
          const kelasTerpakai = (data.kelas || []).map(function (item) {
            return String(item.kelas || '').toUpperCase();
          });

          const options = kelasDefault
            .filter(function (kelas) {
              return !kelasTerpakai.includes(kelas);
            })
            .map(function (kelas) {
              return {
                value: kelas,
                text: `Kelas ${kelas}`
              };
            });

          if (options.length === 0) {
            setOptions(kelasSelect, '-- Semua kelas sudah dibuat --', []);
            kelasSelect.disabled = true;
            return;
          }

          setOptions(kelasSelect, '-- Kelas --', options);
          kelasSelect.disabled = false;

          if (selectedValue) {
            kelasSelect.value = selectedValue;
          }

          refreshSelect2(kelasSelect);
        })
        .catch(function () {
          resetKelas();
        });
    }

    tahunSelect.addEventListener('change', function () {
      enableProdiIfReady();
      resetMatkul();
      resetKelas();
    });

    prodiSelect.addEventListener('change', function () {
      loadMatkul();
    });

    matkulSelect.addEventListener('change', function () {
      loadKelas();
    });

    enableProdiIfReady();

    if (tahunSelect.value && oldProdi) {
      prodiSelect.value = oldProdi;
      prodiSelect.disabled = false;
      refreshSelect2(prodiSelect);
      loadMatkul(oldMatkul);
    }
  });
</script>
@endsection
