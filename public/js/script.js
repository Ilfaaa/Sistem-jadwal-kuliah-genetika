$(document).ready(function () {

  function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
      return $1.toUpperCase();
    });
  }

  Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
      what = a[--L];
      while ((ax = this.indexOf(what)) !== -1) {
        this.splice(ax, 1);
      }
    }
    return this;
  };

  function resetSelect(selector, placeholder, disabled = true) {
    if ($(selector).length === 0) return;

    $(selector).html(`<option value="">${placeholder}</option>`);
    $(selector).val('').trigger('change');

    if (disabled) {
      $(selector).attr('disabled', 'disabled');
    } else {
      $(selector).removeAttr('disabled');
    }
  }

  // ajax create jadwal home
  $(document).on('change', '#search-tahun', function () {
    $('#default-tahun-option').remove();

    let tahun = $(this).val();

    $.ajax({
      url: "/home/action",
      method: 'GET',
      data: { tahun },
      dataType: 'json',
      success: function (response) {
        const jadwalGanjil = response.ganjil || [];
        const jadwalGenap = response.genap || [];
        const tahunAjaran = response.tahun;
        const tahunAjaranLink = tahunAjaran.split('/').join('-');

        let jadwalGanjilBody_HTML = ``;
        let jadwalGenapBody_HTML = ``;

        if (jadwalGanjil.length == 0) {
          jadwalGanjilBody_HTML = `<tr><td scope="row" colspan="10" class="text-center text-bold text-danger">DATA NOT FOUND! Silahkan Generate Jadwal atau Hubungi Admin.</td></tr>`;
        } else {
          jadwalGanjilBody_HTML = `${jadwalGanjil.map(function (jadwal, i) {
            return `<tr>
              <td scope="row">${i + 1}</td>
              <td scope="row">${ucwords(jadwal.matkul)}</td>
              <td scope="row">${ucwords(jadwal.dosen)}</td>
              <td scope="row">${jadwal.kelas}</td>
              <td scope="row">${jadwal.jumlah_sks}</td>
              <td scope="row">${ucwords(jadwal.nama_ruang)}</td>
              <td scope="row">${ucwords(jadwal.hari)}</td>
              <td scope="row">${jadwal.jam_masuk}</td>
              <td scope="row">${jadwal.jam_keluar}</td>
            </tr>`;
          }).join("")}`;
        }

        $("#jadwal_ganjil_wrap").html(` 
          <div class="row">
            <div class="col-md-12">
              <div class="card text-choThem">
                <div class="card-header bg-greenTheme">
                  <h3 class="card-title text-white text-bold">
                    Tabel Jadwal Perkuliahan Semester <b>Ganjil ${tahunAjaran}</b>
                    <a href="/home/export_excel/ganjil/${tahunAjaranLink}" class="badge bg-maroon ml-1" target="_blank">
                      <i class="far fa-file-excel mr-1"></i>EXCEL
                    </a>
                  </h3>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-bordered text-center">
                    <thead>
                      <tr>
                        <th scope="col">NO</th>
                        <th scope="col">Mata Kuliah</th>
                        <th scope="col">Dosen Pengajar</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Jumlah sks</th>
                        <th scope="col">Ruangan</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Jam Masuk</th>
                        <th scope="col">Jam Keluar</th>
                      </tr>
                    </thead>
                    <tbody>${jadwalGanjilBody_HTML}</tbody>
                  </table>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>
          </div>
        `);

        if (jadwalGenap.length == 0) {
          jadwalGenapBody_HTML = `<tr><td scope="row" colspan="10" class="text-center text-bold text-danger">DATA NOT FOUND! Silahkan Generate Jadwal atau Hubungi Admin.</td></tr>`;
        } else {
          jadwalGenapBody_HTML = `${jadwalGenap.map(function (jadwal, i) {
            return `<tr>
              <td scope="row">${i + 1}</td>
              <td scope="row">${ucwords(jadwal.matkul)}</td>
              <td scope="row">${ucwords(jadwal.dosen)}</td>
              <td scope="row">${jadwal.kelas}</td>
              <td scope="row">${jadwal.jumlah_sks}</td>
              <td scope="row">${ucwords(jadwal.nama_ruang)}</td>
              <td scope="row">${ucwords(jadwal.hari)}</td>
              <td scope="row">${jadwal.jam_masuk}</td>
              <td scope="row">${jadwal.jam_keluar}</td>
            </tr>`;
          }).join("")}`;
        }

        $("#jadwal_genap_wrap").html(`
          <div class="row">
            <div class="col-md-12">
              <div class="card text-choThem">
                <div class="card-header bg-greenTheme">
                  <h3 class="card-title text-white text-bold">
                    Tabel Jadwal Perkuliahan Semester <b>Genap ${tahunAjaran}</b>
                    <a href="/home/export_excel/genap/${tahunAjaranLink}" class="badge bg-maroon ml-1" target="_blank">
                      <i class="far fa-file-excel mr-1"></i>EXCEL
                    </a>
                  </h3>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-bordered text-center">
                    <thead>
                      <tr>
                        <th scope="col">NO</th>
                        <th scope="col">Mata Kuliah</th>
                        <th scope="col">Dosen Pengajar</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Jumlah sks</th>
                        <th scope="col">Ruangan</th>
                        <th scope="col">Hari</th>
                        <th scope="col">Jam Masuk</th>
                        <th scope="col">Jam Keluar</th>
                      </tr>
                    </thead>
                    <tbody>${jadwalGenapBody_HTML}</tbody>
                  </table>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>
          </div>
        `);
      }
    });
  });

  // ajax create waktu
  $(document).on('change', '#select-hari', function () {
    $('#select-jam').removeAttr("disabled");
    $('.default-select').remove();

    let kode_hari = $(this).val();

    $.ajax({
      url: "/managewaktu/create/action",
      method: 'GET',
      data: { kode_hari: kode_hari },
      dataType: 'json',
      success: function (data) {
        const availableHours = data.availableHours || [];

        $("#select-jam").html(`
          ${availableHours.map(function (hour) {
            return `<option value="${hour.kode_jam}">${hour.jam}</option>`;
          }).join("")}
        `);
      }
    });
  });

  // ajax create kelas: Tahun Ajaran -> Prodi -> Matkul -> Kelas
  $(document).on('change', '#select-tahun_ajaran', function () {
    $('#select-prodi').removeAttr("disabled");
    $('#select-prodi').val('').trigger("change");

    resetSelect('#select-matkul', '-- Mata Kuliah --', true);
    resetSelect('#select-kelas', '-- Kelas --', true);
  });

  $(document).on('change', '#select-prodi', function () {
    let prodi = $(this).val();
    let tahun_ajaran = $("#select-tahun_ajaran").val();

    resetSelect('#select-matkul', '-- Mata Kuliah --', true);
    resetSelect('#select-kelas', '-- Kelas --', true);

    if (!prodi || !tahun_ajaran) {
      return;
    }

    $.ajax({
      url: "/managekuliah/managekelas/create/action",
      method: 'GET',
      data: { prodi, tahun_ajaran },
      dataType: 'json',
      success: function (response) {
        const allMatkul = response.allMatkul || [];

        $("#select-matkul").removeAttr("disabled");
        $("#select-matkul").html(`
          <option value="">-- Pilih Mata Kuliah --</option>
          ${allMatkul.map(function (matkul) {
            return `<option value="${matkul.kode_matkul}-${matkul.nama_matkul}">${ucwords(matkul.nama_matkul)}</option>`;
          }).join("")}
        `);
      }
    });
  });

  $(document).on('change', '#select-matkul', function () {
    let matkul = $('#select-matkul').val();
    let tahun_ajaran = $("#select-tahun_ajaran").val();

    resetSelect('#select-kelas', '-- Kelas --', true);

    if (!matkul || !tahun_ajaran) {
      return;
    }

    $('#select-kelas').removeAttr("disabled");

    $.ajax({
      url: "/managekuliah/managekelas/create/action",
      method: 'GET',
      data: { matkul, tahun_ajaran },
      dataType: 'json',
      success: function (data) {
        const allKelas = data.kelas || [];
        let listOfKelas = ['A', 'B', 'C', 'D', 'E'];

        for (let i = 0; i < listOfKelas.length; i++) {
          for (let j = 0; j < allKelas.length; j++) {
            if (listOfKelas[i] == allKelas[j].kelas) {
              listOfKelas.remove(allKelas[j].kelas);
            }
          }
        }

        if (allKelas.length >= 5) {
          $("#select-kelas").html(`<option value="">Seluruh Kelas Sudah Terpenuhi</option>`);
        } else {
          $("#select-kelas").html(`
            <option value="">-- Pilih Kelas --</option>
            ${listOfKelas.map(function (a) {
              return `<option value="${a}">${a}</option>`;
            }).join("")}
          `);
        }
      }
    });
  });

  // Edit kelas: dosen manual tetap maksimal 2
  $(document).on('change', '#select-dosen-edit', function () {
    const selected = $(this).val() || [];

    if (selected.length > 2) {
      selected.pop();
      $(this).val(selected).trigger('change');
      alert('Maksimal hanya boleh memilih 2 dosen pengajar.');
    }
  });

  // radio semester change
  $('input[type=radio][name=radioSemester]').change(function () {
    if (this.value == 1 || this.value == 2) {
      window.semester = this.value;

      $("#select-kelas_1").html(`<option value="" selected id="default-select-kelas_1">-- Kelas Yang Diajar --</option>`);
      $("#select-kelas_1").attr({ "disabled": "disabled" });

      if ($("#default-select-dosen_1").length == 0) {
        $("#select-dosen_1").prepend(`<option value="" id="default-select-dosen_1" selected>-- Silahkan Pilih Dosen --</option>`);
      }
    }
  });

  // Ajax Prioritas Dosen
  const prioritasDosenMax = $('#maxKelas').val();

  $(document).on('change', `#the_tahun_ajaran`, function () {
    $(`#the_tahun_ajaran_default`).remove();
    window.tahun_ajaran = this.value;
    $(`#radioganjil`).prop('checked', false);
    $(`#radiogenap`).prop('checked', false);
  });

  for (let i = 1; i <= prioritasDosenMax; i++) {

    $(document).on('change', `#select-dosen_${i}`, function () {
      $(`#select-kelas_${i}`).removeAttr("disabled");
      $(`#select-hari_${i}`).removeAttr("disabled");
      $(`#default-select-dosen_${i}`).remove();
      $(`#default-select-kelas_${i}`).remove();
      $(`#default-select-hari_${i}`).remove();

      let dosen = $(this).val();

      $.ajax({
        url: "/generatejadwal/action",
        method: 'GET',
        data: {
          dosen: dosen,
          semester: window.semester,
          tahun_ajaran: window.tahun_ajaran
        },
        dataType: 'json',
        success: function (response) {
          const allKelas = response.allKelas || [];

          $(`#select-kelas_${i}`).html(`
            ${allKelas.map(function (kelas) {
              const kodeKelas = kelas.id_kelas;
              const namaMatkul = kelas.kode_matkul || '-';
              const namaKelas = kelas.nama_kelas || '-';
              return `<option value="${kodeKelas}">${ucwords(namaMatkul)} - ${namaKelas}</option>`;
            }).join("")}
          `);
        }
      });
    });

    $(document).on('change', `#select-hari_${i}`, function () {
      $(`#select-jam_${i}`).removeAttr("disabled");
      $(`#default-select-jam_${i}`).remove();

      let hari = $(this).val();

      $.ajax({
        url: "/generatejadwal/action",
        method: 'GET',
        data: { hari },
        dataType: 'json',
        success: function (response) {
          const allJam = response.allJam || [];

          $(`#select-jam_${i}`).html(`
            ${allJam.map(function (jam) {
              return `<option value="${jam.kode_jam}">${jam.jam}</option>`;
            }).join("")}
          `);
        }
      });
    });
  }

  // show prioritas dosen
  $(`.dosen-request-wrap-1`).removeClass('d-none');

  let count = 2;

  $(document).on('click', '.button-add', function () {
    if (count == prioritasDosenMax) {
      Swal.fire(`Maksimal prioritas dosen ${prioritasDosenMax}!`);
    }

    $(`.dosen-request-wrap-${count++}`).removeClass('d-none');
  });

  let path = window.location.pathname;
  const thePath = path.split("/");

  $(".nav-treeview-container").hide();
  $(".optional-input").hide();

  if (thePath[1] == 'managekuliah') {
    $('.nav-treeview-container.treeview-kuliah').show();
    $(".arrow-kuliah").addClass('rotate-n90d');
  }

  if (thePath[1] == 'managewaktu') {
    $('.nav-treeview-container.treeview-waktu').show();
    $(".arrow-waktu").addClass('rotate-n90d');
  }

  $(".opsiLainBtn").click(function (e) {
    e.preventDefault();

    if ($('.opsiLainBtn .fa-arrow-circle-right').hasClass('rotate-90d')) {
      $('.opsiLainBtn .fa-arrow-circle-right').removeClass('rotate-90d');
    } else {
      $('.opsiLainBtn .fa-arrow-circle-right').addClass('rotate-90d');
    }

    $(".optional-input").toggle(500);
  });
  $(".menu-kuliah-toggle").click(function (e) {
    e.preventDefault();
    var arrow = $(this).find("i.fa-angle-left.arrow-kuliah");

    if (arrow.hasClass('rotate-n90d')) {
      arrow.removeClass('rotate-n90d');
    } else {
      arrow.addClass('rotate-n90d');
    }

    $(".nav-treeview-container.treeview-kuliah").toggle(500);
  });

  $(".menu-waktu-toggle").click(function (e) {
    e.preventDefault();
    var arrow = $(this).find("i.fa-angle-left.arrow-waktu");

    if (arrow.hasClass('rotate-n90d')) {
      arrow.removeClass('rotate-n90d');
    } else {
      arrow.addClass('rotate-n90d');
    }

    $(".nav-treeview-container.treeview-waktu").toggle(500);
  });

  $(".genBtn").click(function () {
    $(this).addClass('genBtnAfter');
  });

  $('.select2').select2();

  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });

  if ($.isFunction($.fn.colorpicker)) {
    $('.my-colorpicker1').colorpicker();
  }

  // Tooltip Dosen
  function initDosenTooltips() {
    $('.class-dosen-badge').each(function () {
      var kode = $(this).attr('data-kode');
      if (kode && window.lecturersMap && window.lecturersMap[kode]) {
        var rawName = window.lecturersMap[kode];
        // Title case the name
        var titleName = rawName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        $(this).attr('title', titleName);
        $(this).attr('data-toggle', 'tooltip');
        $(this).css('cursor', 'pointer');
      }
    });
    $('[data-toggle="tooltip"]').tooltip();
  }
  initDosenTooltips();

});

// search keyword
let btn_search = document.querySelector('.btn-default');
let has_search = document.querySelector('.has_search');

if (has_search && has_search.value && btn_search) {
  btn_search.innerHTML = `<i class="fas fa-sync-alt"></i>`;
}