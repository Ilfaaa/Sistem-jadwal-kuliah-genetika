$(document).ready(function () {

  // Capitalize
  function ucwords(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
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

  // ajax create jadwal
  $(document).on('change', '#search-tahun', function () {
    $('#default-tahun-option').remove()

    let tahun = $(this).val()
   
    const ajax_jadwal = (tahun) => {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: "/home/action",
          method: 'GET',
          data: { tahun },
          dataType: 'json',
          success: function (data) {
            resolve(data);
          }
        })
      })
    }

    ajax_jadwal(tahun).then(async function (response) {
      
      const jadwalGanjil = response.ganjil
      const jadwalGenap = response.genap
      const tahunAjaran = response.tahun
      const tahunAjaranLinkTemp = tahunAjaran.split('/')
      const tahunAjaranLink = tahunAjaranLinkTemp.join('-');
      console.log(tahunAjaranLink);

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
        </tr>`
        }).join("")}`;
      }

      $("#jadwal_ganjil_wrap").html(` 
        <div class="row">
        <div class="col-md-12">
            <div class="card text-choThem">
                <div class="card-header bg-greenTheme">
                    <h3 class="card-title text-whiteTheme">Tabel Jadwal Perkuliahan Semester <b>Ganjil ${tahunAjaran}</b> <a href="/home/export_excel/ganjil/${tahunAjaranLink}" class="badge bg-maroon ml-1" target="_blank"><i class="far fa-file-excel mr-1"></i>EXCEL</a></h3>
                    
                </div>
                <!-- /.card-header -->
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
                    <tbody>
                        ${jadwalGanjilBody_HTML}
                    </tbody>
                </table>
                </div>
                <div class="card-footer">
                <!-- /.card -->
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
          </tr>`
        }).join("")}`;
      }
  
      $("#jadwal_genap_wrap").html(`
          <div class="row">
          <div class="col-md-12">
              <div class="card text-choThem">
                  <div class="card-header bg-greenTheme">
                      <h3 class="card-title text-whiteTheme">Tabel Jadwal Perkuliahan Semester <b>Genap ${tahunAjaran}</b> <a href="/home/export_excel/genap/${tahunAjaranLink}" class="badge bg-maroon ml-1" target="_blank"><i class="far fa-file-excel mr-1"></i>EXCEL</a></h3>
                  </div>
                  <!-- /.card-header -->
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
                      <tbody>
                          ${jadwalGenapBody_HTML}
                      </tbody>
                  </table>
                  </div>
                  <div class="card-footer">
                  <!-- /.card -->
                    </div>
                </div>
            </div>
          `);
     
    })
  })

  // ajax create waktu
  $(document).on('change', '#select-hari', function () {
    $('#select-jam').removeAttr("disabled")
    $('.default-select').remove()
  
    let kode_hari = $(this).val()
    $.ajax({
      url: "/managewaktu/create/action",
      method: 'GET',
      data: { kode_hari: kode_hari },
      dataType: 'json',
      success: function (data) {
        console.log(data.availableHours)
        const availableHours = data.availableHours
  
        $("#select-jam").html(`
              ${availableHours.map(function (hour) {
          return "<option value='" + hour.kode_jam + "'>" + hour.jam + "</option>"
        }).join("")}
           `);
      }
    })
  
  })
  
  // ajax create kelas
  $(document).on('change', '#select-tahun_ajaran', function () {
    $('#select-prodi').removeAttr("disabled");
    $('#select-prodi').val('').trigger("change");
    $('#select-matkul').val('').trigger("change");
    $('#select-dosen').val('').trigger("change");
    // $('#select-dosen  option:selected').attr('disabled','disabled').siblings().removeAttr('disabled');
    // $('#select-prodi option:first').prop('selected',true);
    // <option value="" selected class="default-select" id="default-select-prodi">-- Program Studi --</option>
    // $('#default-select-prodi').remove()

  })
  
  
  $(document).on('change', '#select-prodi', function () {
    $('#select-matkul').removeAttr("disabled")
    $('#select-dosen').removeAttr("disabled")
    // $('.default-select').remove()
  
    let prodi = $(this).val()
    let tahun_ajaran = $("#select-tahun_ajaran").val()

    const ajax_kelas = (prodi, tahun_ajaran) => {
      return new Promise((resolve, reject) => {
        $.ajax({
          url: "/managekuliah/managekelas/create/action",
          method: 'GET',
          data: { prodi, tahun_ajaran },
          dataType: 'json',
          success: function (data) {
            resolve(data);
          }
        })
      })
    }

    ajax_kelas(prodi, tahun_ajaran).then(async function (response) {
        
      const allDosen = response.allDosen
      const allMatkul = response.allMatkul

      $("#select-dosen").html(`
        <option value='' class="default-option">-- Pilih Dosen --</option>
          ${allDosen.map(function (dosen) {
        return `<option value='${dosen.nama}'>${ucwords(dosen.nama)}</option>`
      }).join("")}
        `);

      $("#select-matkul").html(`
        <option value='' class="default-option">-- Pilih Mata Kuliah --</option>
          ${allMatkul.map(function (matkul) {
        return `<option value='${matkul.kode_matkul}-${matkul.nama_matkul}'>${ucwords(matkul.nama_matkul)}</option>`
      }).join("")}
        `);

    })
  })

  $(document).on('change', '#select-matkul', function () {

    // $('.default-option').remove()
    
    let matkul = $('#select-matkul').val()
    let tahun_ajaran = $("#select-tahun_ajaran").val()

    $('#select-kelas').removeAttr("disabled")


    $.ajax({
      url: "/managekuliah/managekelas/create/action",
      method: 'GET',
      data: { matkul, tahun_ajaran },
      dataType: 'json',
      success: function (data) {
        console.log(data);
        const allKelas = data.kelas;

        let listOfKelas = ['A', 'B', 'C', 'D', 'E'];

        for (let i = 0; i < listOfKelas.length; i++) {
          for (let j = 0; j < allKelas.length; j++) {
            if (listOfKelas[i] == allKelas[j].kelas) {
              listOfKelas.remove(allKelas[j].kelas);
            }
            
          }
        }

        console.log(listOfKelas);

        if (allKelas.length == 5) {
          $("#select-kelas").html(`<option value="">Seluruh Kelas Sudah Terpenuhi</option>`);
        } else {
          $("#select-kelas").html(`
          ${listOfKelas.map(function (a) {
            return `<option value='${a}'>${a}</option>`
          }).join("")}
          `)
        }
        
        //   if (allKelas.length == 0) {
        //     $("#select-kelas").html(`<option value="A">A</option>`);
        //   } else if (allKelas.length == 1) {
        //     if (allKelas[0].kelas == 'A') {
        //       $("#select-kelas").html(`<option value="B">B</option>`);
        //     } else {
        //       $("#select-kelas").html(`<option value="A">A</option>`); 
        //     }
        //   } else {
        //     $("#select-kelas").html(`<option value="">Kelas A dan B Sudah Ada</option>`);
        //   }
        
      }
    })
  })


  // radio semester change
  $('input[type=radio][name=radioSemester]').change(function () {
    
    if (this.value == 1) {

      window.semester = this.value;
      $("#select-kelas_1").html(`<option value="" selected id="default-select-kelas_1">-- Kelas Yang Diajar --</option>`);
      $("#select-kelas_1").attr({
        "disabled": "disabled"
      });
      if ($("#default-select-dosen_1").length == 0) {
        $("#select-dosen_1").prepend(`<option value="" id="default-select-dosen_1" selected>-- Silahkan Pilih Dosen --</option>`);
      }
      
    }
    else if (this.value == 2) {
      window.semester = this.value;
      $("#select-kelas_1").html(`<option value="" selected id="default-select-kelas_1">-- Kelas Yang Diajar --</option>`);
      $("#select-kelas_1").attr({
        "disabled": "disabled"
      });
      if ($("#default-select-dosen_1").length == 0) {
        $("#select-dosen_1").prepend(`<option value="" id="default-select-dosen_1" selected>-- Silahkan Pilih Dosen --</option>`);
      }
    }
  });

  // Ajax Prioritas Dosen
  // change nama dosen

  const prioritasDosenMax = $('#maxKelas').val();

  console.log(prioritasDosenMax)

  $(document).on('change', `#the_tahun_ajaran`, function () {
    $(`#the_tahun_ajaran_default`).remove();
    window.tahun_ajaran = this.value;
    $(`#radioganjil`).prop('checked',false);
    $(`#radiogenap`).prop('checked',false);
  })

  for (let i = 1; i <= prioritasDosenMax; i++) { 

  $(document).on('change', `#select-dosen_${i}`, function () { 
    
    $(`#select-kelas_${i}`).removeAttr("disabled")
    $(`#select-hari_${i}`).removeAttr("disabled")
    $(`#default-select-dosen_${i}`).remove()
    $(`#default-select-kelas_${i}`).remove()
    $(`#default-select-hari_${i}`).remove()

    let dosen = $(this).val()

    console.log(dosen);
     
      const ajax_dosen = (dosen, semester, tahun_ajaran) => {
        return new Promise((resolve, reject) => {
          $.ajax({
            url:"/generatejadwal/action",
            method:'GET',
            data:{dosen, semester, tahun_ajaran},
            dataType:'json',
            success: function(data)
            {
              resolve(data);
            }
          })    
        })
    }
    
    ajax_dosen(dosen, window.semester, window.tahun_ajaran).then(async function (response) {
        
      const allKelas = response.allKelas

      console.log(allKelas);

      $(`#select-kelas_${i}`).html(`
        ${allKelas.map(function (kelas) {
          return `<option value='${kelas.kode_kelas}'>${ucwords(kelas.nama_matkul)} - ${kelas.kelas}</option>`
        }).join("")}
      `);
    })
  })

  // change hari ngajar
  $(document).on('change', `#select-hari_${i}`, function () { 
    $(`#select-jam_${i}`).removeAttr("disabled")
    $(`#default-select-jam_${i}`).remove()

    let hari = $(this).val()
    console.log(hari);
     
      const ajax_hari = (hari) => {
        return new Promise((resolve, reject) => {
          $.ajax({
            url:"/generatejadwal/action",
            method:'GET',
            data:{hari},
            dataType:'json',
            success: function(data)
            {
              resolve(data);
            }
          })    
        })
    }
    
    ajax_hari(hari).then(async function (response) {
        
      const allJam = response.allJam

      console.log(allJam);

      $(`#select-jam_${i}`).html(`
        ${allJam.map(function (jam) {
          return `<option value='${jam.kode_jam}'>${jam.jam}</option>`
        }).join("")}
      `);
    })
  })

}

  // show prioritas dosen
  $(`.dosen-request-wrap-1`).removeClass('d-none');
  let count = 2;  
  $(document).on('click', '.button-add', function () { 
    if (count == (prioritasDosenMax)) {
      Swal.fire(`Maksimal prioritas dosen ${prioritasDosenMax}!`);
    }
    $(`.dosen-request-wrap-${count++}`).removeClass('d-none');
  })
  



  let path = window.location.pathname
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
    e.preventDefault()
    if ($('.opsiLainBtn .fa-arrow-circle-right').hasClass('rotate-90d')) {
      $('.opsiLainBtn .fa-arrow-circle-right').removeClass('rotate-90d')
    } else {
      $('.opsiLainBtn .fa-arrow-circle-right').addClass('rotate-90d')
    }


    $(".optional-input").toggle(500);
  })

  $("i.fa-angle-left.arrow-kuliah").click(function (e) {
    e.preventDefault();
    
    if ($(this).hasClass('rotate-n90d')) {
      $(this).removeClass('rotate-n90d')
    } else {
      $(this).addClass('rotate-n90d')
    }
    $(".nav-treeview-container.treeview-kuliah").toggle(500);
  });

  $("i.fa-angle-left.arrow-waktu").click(function (e) {
    e.preventDefault();
    
    if ($(this).hasClass('rotate-n90d')) {
      $(this).removeClass('rotate-n90d')
    } else {
      $(this).addClass('rotate-n90d')
    }
    $(".nav-treeview-container.treeview-waktu").toggle(500);
  });


  function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
  }

  // gen button click

  $(".genBtn").click(function () { 
    $(this).addClass('genBtnAfter')
  })

  //Initialize Select2 Elements
  $('.select2').select2()
  
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  })

  //Colorpicker
  $('.my-colorpicker1').colorpicker()


  
})

  // search keyword
  let btn_search = document.querySelector('.btn-default')
  let has_search = document.querySelector('.has_search')

  if (has_search.value) {
    btn_search.innerHTML = `<i class="fas fa-sync-alt"></i>`
  }


 