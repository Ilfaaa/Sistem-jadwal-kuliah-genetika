<?php

use App\Http\Controllers\BlockingJadwalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjadwalankuliahController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'CheckLoginMiddleware'], function () {
    Route::get('/', 'AuthController@index');
    Route::post('/register', [AuthController::class, 'registerStore']);
    Route::post('/login', [AuthController::class, 'loginStore']);
});

Route::group(['middleware' => 'CheckLogoutMiddleware'], function () {

    Route::get('/home/dashboard', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::get('/home/action', 'HomeController@tampilkan_jadwal');
    Route::get('/home/export_excel/{semester}/{tahun}', 'HomeController@export_excel');

    Route::get('myprofile', 'ProfileController@index')->name('myprofile');
    Route::get('editprofile', 'ProfileController@editprofile')->name('editprofile');
    Route::patch('editprofile', 'ProfileController@updateprofile');
    Route::get('editpassword', 'ProfileController@editpassword')->name('editpassword');
    Route::patch('editpassword', 'ProfileController@updatepassword');

    Route::group(['middleware' => 'CheckAdminMiddleware'], function () {

        Route::get('/manageusers', 'ManageusersController@index');
        Route::get('/manageusers/create', 'ManageusersController@create');
        Route::post('/manageusers/keyword', 'ManageusersController@index');
        Route::post('/manageusers', 'ManageusersController@store');
        Route::delete('/manageusers/{id}', 'ManageusersController@destroy');
        Route::get('/manageusers/{id}/edit', 'ManageusersController@edit');
        Route::patch('/manageusers/{id}', 'ManageusersController@update');

        Route::get('/allrequests', 'AllrequestsController@index');
        Route::get('/allrequests/kuliah/{id}/accept', 'AllrequestsController@acceptKuliah');
        Route::get('/allrequests/kuliah/{id}/reject', 'AllrequestsController@rejectKuliah');
        Route::get('/allrequests/ruang/{id}/accept', 'AllrequestsController@acceptRuang');
        Route::get('/allrequests/ruang/{id}/reject', 'AllrequestsController@rejectRuang');
        Route::get('/allrequests/waktu/{id}/accept', 'AllrequestsController@acceptWaktu');
        Route::get('/allrequests/waktu/{id}/reject', 'AllrequestsController@rejectWaktu');
    });

    Route::get('/blocking-jadwal', [BlockingJadwalController::class, 'index']);
    Route::post('/blocking-jadwal', [BlockingJadwalController::class, 'store']);
    Route::post('/blocking-jadwal/delete', [BlockingJadwalController::class, 'destroy']);

    Route::get('/managekuliah', 'ManagekuliahController@index');
    Route::get('/managekuliah/create', 'ManagekuliahController@create');
    Route::get('/managekuliah/create/action', 'ManagekuliahController@create_action')->name('managekuliah.create.action');
    Route::post('/managekuliah/keyword', 'ManagekuliahController@index');
    Route::post('/managekuliah', 'ManagekuliahController@store');
    Route::delete('/managekuliah/{kode_kuliah}', 'ManagekuliahController@destroy');
    Route::get('/managekuliah/{kode_kuliah}/edit', 'ManagekuliahController@edit');
    Route::patch('/managekuliah/{kode_kuliah}', 'ManagekuliahController@update');

    Route::get('/managekuliah/managedosen', 'ManagedosenController@index');
    Route::get('/managekuliah/managedosen/create', 'ManagedosenController@create');
    Route::post('/managekuliah/managedosen/keyword', 'ManagedosenController@index');
    Route::post('/managekuliah/managedosen', 'ManagedosenController@store');
    Route::delete('/managekuliah/managedosen/{kode_dosen}', 'ManagedosenController@destroy');
    Route::get('/managekuliah/managedosen/{kode_dosen}/edit', 'ManagedosenController@edit');
    Route::patch('/managekuliah/managedosen/{kode_dosen}', 'ManagedosenController@update');

    Route::get('/managekuliah/managematkul', 'ManagematkulController@index');
    Route::get('/managekuliah/managematkul/create', 'ManagematkulController@create');
    Route::post('/managekuliah/managematkul/keyword', 'ManagematkulController@index');
    Route::post('/managekuliah/managematkul', 'ManagematkulController@store');
    Route::delete('/managekuliah/managematkul/{kode_matkul}/{tahun_ajaran}', 'ManagematkulController@destroy');
    Route::get('/managekuliah/managematkul/{kode_matkul}/{tahun_ajaran}/edit', 'ManagematkulController@edit');
    Route::patch('/managekuliah/managematkul/{kode_matkul}/{tahun_ajaran}', 'ManagematkulController@update');

    Route::get('/managekuliah/manageprodi', 'ManageprodiController@index');
    Route::get('/managekuliah/manageprodi/create', 'ManageprodiController@create');
    Route::post('/managekuliah/manageprodi/keyword', 'ManageprodiController@index');
    Route::post('/managekuliah/manageprodi', 'ManageprodiController@store');
    Route::delete('/managekuliah/manageprodi/{id}', 'ManageprodiController@destroy');
    Route::get('/managekuliah/manageprodi/{id}/edit', 'ManageprodiController@edit');
    Route::patch('/managekuliah/manageprodi/{id}', 'ManageprodiController@update');

    Route::get('/managekuliah/managekelas', 'ManagekelasController@index');
    Route::get('/managekuliah/managekelas/create', 'ManagekelasController@create');
    Route::get('/managekuliah/managekelas/create/action', 'ManagekelasController@create_action');
    Route::post('/managekuliah/managekelas/keyword', 'ManagekelasController@index');
    Route::post('/managekuliah/managekelas', 'ManagekelasController@store');
    Route::delete('/managekuliah/managekelas/{kode_kelas}/{tahun_ajaran}', 'ManagekelasController@destroy');
    Route::get('/managekuliah/managekelas/{kode_kelas}/{tahun_ajaran}/edit', 'ManagekelasController@edit');
    Route::patch('/managekuliah/managekelas/{kode_kelas}/{tahun_ajaran}', 'ManagekelasController@update');

    Route::get('/manageruang', 'ManageruangController@index');
    Route::get('/manageruang/create', 'ManageruangController@create');
    Route::post('/manageruang/keyword', 'ManageruangController@index');
    Route::post('/manageruang', 'ManageruangController@store');
    Route::delete('/manageruang/{kode_ruang}', 'ManageruangController@destroy');
    Route::get('/manageruang/{kode_ruang}/edit', 'ManageruangController@edit');
    Route::patch('/manageruang/{kode_ruang}', 'ManageruangController@update');

    Route::get('/managewaktu', 'ManagewaktuController@index');
    Route::get('/managewaktu/create', 'ManagewaktuController@create');
    Route::get('/managewaktu/create/action', 'ManagewaktuController@create_action');
    Route::post('/managewaktu/keyword', 'ManagewaktuController@index');
    Route::post('/managewaktu', 'ManagewaktuController@store');
    Route::delete('/managewaktu/{kode_waktu}', 'ManagewaktuController@destroy');
    Route::get('/managewaktu/{kode_waktu}/edit', 'ManagewaktuController@edit');
    Route::patch('/managewaktu/{kode_waktu}', 'ManagewaktuController@update');

    Route::get('/managewaktu/managehari', 'ManagehariController@index');
    Route::get('/managewaktu/managehari/create', 'ManagehariController@create');
    Route::post('/managewaktu/managehari/keyword', 'ManagehariController@index');
    Route::post('/managewaktu/managehari', 'ManagehariController@store');
    Route::delete('/managewaktu/managehari/{kode_hari}', 'ManagehariController@destroy');
    Route::get('/managewaktu/managehari/{kode_hari}/edit', 'ManagehariController@edit');
    Route::patch('/managewaktu/managehari/{kode_hari}', 'ManagehariController@update');

    Route::get('/managewaktu/managejam', 'ManagejamController@index');
    Route::get('/managewaktu/managejam/create', 'ManagejamController@create');
    Route::post('/managewaktu/managejam/keyword', 'ManagejamController@index');
    Route::post('/managewaktu/managejam', 'ManagejamController@store');
    Route::delete('/managewaktu/managejam/{kode_jam}', 'ManagejamController@destroy');
    Route::get('/managewaktu/managejam/{kode_jam}/edit', 'ManagejamController@edit');
    Route::patch('/managewaktu/managejam/{kode_jam}', 'ManagejamController@update');

    Route::get('/managetahunajaran', 'ManageTahunAjaranController@index');
    Route::get('/managetahunajaran/create', 'ManageTahunAjaranController@create');
    Route::post('/managetahunajaran', 'ManageTahunAjaranController@store');
    Route::delete('/managetahunajaran/{id}', 'ManageTahunAjaranController@destroy');

    Route::group(['middleware' => 'CheckAdminMiddleware'], function () {
        Route::get('/generatejadwal', [PenjadwalankuliahController::class, 'generatejadwalform'])->name('generatejadwal.form');
        Route::get('/generatejadwal/action', [PenjadwalankuliahController::class, 'generate_action'])->name('generatejadwal.action');
        Route::post('/generatejadwal', [PenjadwalankuliahController::class, 'generatejadwal'])->name('generatejadwal.process');
    });

    Route::get('/hasilgenerate/{jadwal_index}', [PenjadwalankuliahController::class, 'hasilgenerate'])->whereNumber('jadwal_index')->name('hasilgenerate.store');
    Route::get('/hasiljadwal', [PenjadwalankuliahController::class, 'hasiljadwal'])->name('hasiljadwal.index');

    Route::get('/logout', 'AuthController@logout');
});