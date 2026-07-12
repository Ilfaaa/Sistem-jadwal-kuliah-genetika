<?php

use App\Http\Controllers\BlockingJadwalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjadwalankuliahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PenjadwalankuliahController::class, 'hasiljadwal']);

Route::group(['middleware' => 'CheckLoginMiddleware'], function () {
    Route::get('/login', 'AuthController@index')->name('login');
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
        
        // Approvals Routes
        Route::get('/manageusers/approvals', 'ManageusersController@approvals')->name('manageusers.approvals');
        Route::post('/manageusers/approvals/{id}/approve', 'ManageusersController@approve')->name('manageusers.approve');
        Route::post('/manageusers/approvals/{id}/reject', 'ManageusersController@reject')->name('manageusers.reject');

        Route::delete('/manageusers/{id}', 'ManageusersController@destroy');
        Route::get('/manageusers/{id}/edit', 'ManageusersController@edit');
        Route::patch('/manageusers/{id}', 'ManageusersController@update');

        // Assign Dosen Routes
        Route::get('/manageusers/{id}/assign-dosen', 'ManageusersController@assignDosenForm')->name('manageusers.assign-dosen');
        Route::post('/manageusers/{id}/assign-dosen', 'ManageusersController@assignDosen')->name('manageusers.assign-dosen.store');
        Route::post('/manageusers/{id}/unassign-dosen', 'ManageusersController@unassignDosen')->name('manageusers.unassign-dosen');

    });

    Route::group(['middleware' => 'CheckNotMahasiswaMiddleware'], function () {

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


        Route::get('/managehari', 'ManagehariController@index');
        Route::get('/managehari/create', 'ManagehariController@create');
        Route::post('/managehari/keyword', 'ManagehariController@index');
        Route::post('/managehari', 'ManagehariController@store');
        Route::delete('/managehari/{kode_hari}', 'ManagehariController@destroy');
        Route::get('/managehari/{kode_hari}/edit', 'ManagehariController@edit');
        Route::patch('/managehari/{kode_hari}', 'ManagehariController@update');



        Route::get('/managetahunajaran', 'ManageTahunAjaranController@index');
        Route::get('/managetahunajaran/create', 'ManageTahunAjaranController@create');
        Route::post('/managetahunajaran', 'ManageTahunAjaranController@store');
        Route::delete('/managetahunajaran/{id}', 'ManageTahunAjaranController@destroy');

        Route::group(['middleware' => 'CheckAdminMiddleware'], function () {
            Route::get('/generatejadwal', [PenjadwalankuliahController::class, 'generatejadwalform'])->name('generatejadwal.form');
            Route::get('/generatejadwal/action', [PenjadwalankuliahController::class, 'generate_action'])->name('generatejadwal.action');
            Route::post('/generatejadwal', [PenjadwalankuliahController::class, 'generatejadwal'])->name('generatejadwal.process');
            Route::post('/generatejadwal/pindah-online/{jadwal_index}/{row_index}', [PenjadwalankuliahController::class, 'pindahJadwalOnline'])->name('generatejadwal.pindah-online');
        });
    });

    Route::get('/hasilgenerate/{jadwal_index}', [PenjadwalankuliahController::class, 'hasilgenerate'])->whereNumber('jadwal_index')->name('hasilgenerate.store');

    Route::get('/logout', 'AuthController@logout');
});

Route::get('/hasiljadwal', [PenjadwalankuliahController::class, 'hasiljadwal'])->name('hasiljadwal.index');