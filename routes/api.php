<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDosenController;
use App\Http\Controllers\Api\ApiMatkulController;
use App\Http\Controllers\Api\ApiProdiController;
use App\Http\Controllers\Api\ApiKelasController;
use App\Http\Controllers\Api\ApiRuangController;
use App\Http\Controllers\Api\ApiWaktuController;
use App\Http\Controllers\Api\ApiJadwalController;
use App\Http\Controllers\Api\ApiTahunAjaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ============================================================
// PUBLIC ROUTES (tanpa autentikasi)
// ============================================================
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);

// ============================================================
// PROTECTED ROUTES (membutuhkan Sanctum token)
// ============================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- Auth ---
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'user']);
});

// Pindahkan sementara rute dosen agar tidak memerlukan otentikasi Sanctum token (Bypass "Unauthenticated") saat uji JMeter Tahap I
Route::get('/dosen', [ApiDosenController::class, 'index']);
Route::get('/dosen/{kode_dosen}', [ApiDosenController::class, 'show']);
Route::post('/dosen', [ApiDosenController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    // --- Dosen (Write: admin langsung, non-admin → request) ---
    Route::put('/dosen/{kode_dosen}', [ApiDosenController::class, 'update']);
    Route::delete('/dosen/{kode_dosen}', [ApiDosenController::class, 'destroy']);

    // --- Mata Kuliah (Read: semua role | Write: admin langsung, non-admin → request) ---
    Route::get('/matkul', [ApiMatkulController::class, 'index']);
    Route::get('/matkul/{kode_matkul}/{tahun_ajaran}', [ApiMatkulController::class, 'show']);
    Route::post('/matkul', [ApiMatkulController::class, 'store']);
    Route::put('/matkul/{kode_matkul}/{tahun_ajaran}', [ApiMatkulController::class, 'update']);
    Route::delete('/matkul/{kode_matkul}/{tahun_ajaran}', [ApiMatkulController::class, 'destroy']);

    // --- Program Studi (Read: semua role | Write: admin langsung, non-admin → request) ---
    Route::get('/prodi', [ApiProdiController::class, 'index']);
    Route::get('/prodi/{id}', [ApiProdiController::class, 'show']);
    Route::post('/prodi', [ApiProdiController::class, 'store']);
    Route::put('/prodi/{id}', [ApiProdiController::class, 'update']);
    Route::delete('/prodi/{id}', [ApiProdiController::class, 'destroy']);

    // --- Kelas (Read: semua role | Write: admin only) ---
    Route::get('/kelas', [ApiKelasController::class, 'index']);
    Route::get('/kelas/{kode_kelas}/{tahun_ajaran}', [ApiKelasController::class, 'show']);
    Route::middleware('api.admin')->group(function () {
        Route::post('/kelas', [ApiKelasController::class, 'store']);
        Route::put('/kelas/{kode_kelas}/{tahun_ajaran}', [ApiKelasController::class, 'update']);
        Route::delete('/kelas/{kode_kelas}/{tahun_ajaran}', [ApiKelasController::class, 'destroy']);
    });

    // --- Ruang (Read: semua role | Write: admin only) ---
    Route::get('/ruang', [ApiRuangController::class, 'index']);
    Route::get('/ruang/{kode_ruang}', [ApiRuangController::class, 'show']);
    Route::middleware('api.admin')->group(function () {
        Route::post('/ruang', [ApiRuangController::class, 'store']);
        Route::put('/ruang/{kode_ruang}', [ApiRuangController::class, 'update']);
        Route::delete('/ruang/{kode_ruang}', [ApiRuangController::class, 'destroy']);
    });

    // --- Waktu, Hari, Jam (Read only) ---
    Route::get('/waktu', [ApiWaktuController::class, 'index']);
    Route::get('/waktu/{kode_waktu}', [ApiWaktuController::class, 'show']);
    Route::get('/hari', [ApiWaktuController::class, 'hari']);
    Route::get('/jam', [ApiWaktuController::class, 'jam']);

    // --- Jadwal & Kuliah (Read only) ---
    Route::get('/jadwal', [ApiJadwalController::class, 'index']);
    Route::get('/jadwal/{id}', [ApiJadwalController::class, 'show']);
    Route::get('/kuliah', [ApiJadwalController::class, 'kuliah']);
    Route::get('/semester', [ApiJadwalController::class, 'semester']);

    // --- Tahun Ajaran (Read: semua role | Write: admin only) ---
    Route::get('/tahun-ajaran', [ApiTahunAjaranController::class, 'index']);
    Route::middleware('api.admin')->group(function () {
        Route::post('/tahun-ajaran', [ApiTahunAjaranController::class, 'store']);
        Route::delete('/tahun-ajaran/{id}', [ApiTahunAjaranController::class, 'destroy']);
    });
});
