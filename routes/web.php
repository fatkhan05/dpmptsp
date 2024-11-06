<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailUserController;
use App\Http\Controllers\KategoriIzinController;
use App\Http\Controllers\KategoriPegawaiController;
use App\Http\Controllers\LokasiKantorController;
use App\Http\Controllers\LokasiPegawaiController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\PointTargetController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/pengaturan', [AuthController::class, 'pengaturan'])->name('pengaturan');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pegawai')->group(function () {
        Route::get('/', [DetailUserController::class, 'index'])->name('pegawai');
        Route::post('/form', [DetailUserController::class, 'form'])->name('form_pegawai');
        Route::post('/store', [DetailUserController::class, 'store'])->name('store_pegawai');
        Route::post('/delete', [DetailUserController::class, 'delete'])->name('delete_pegawai');
        Route::post('/import', [DetailUserController::class, 'import'])->name('import_pegawai');
        Route::post('/getFormasi', [DetailUserController::class, 'getFormasi'])->name('getFormasi');
        Route::post('/getKabupaten', [DetailUserController::class, 'getKabupaten'])->name('getKabupaten');
        Route::post('/getKecamatan', [DetailUserController::class, 'getKecamatan'])->name('getKecamatan');
        Route::post('/getDesa', [DetailUserController::class, 'getDesa'])->name('getDesa');
    });

    Route::prefix('penjadwalan_shift')->group(function () {
        Route::get('/', [ShiftController::class, 'penjadwalan'])->name('penjadwalan_shift');
        Route::post('/form_penjadwalan', [ShiftController::class, 'form_penjadwalan'])->name('form_penjadwalan_shift');
        Route::post('/store_penjadwalan', [ShiftController::class, 'store_penjadwalan'])->name('store_penjadwalan_shift');
    });

    Route::prefix('kategori_pegawai')->group(function () {
        Route::get('/', [KategoriPegawaiController::class, 'index'])->name('kategori_pegawai');
        Route::post('/form', [KategoriPegawaiController::class, 'form'])->name('form_kategori_pegawai');
        Route::post('/store', [KategoriPegawaiController::class, 'store'])->name('store_kategori_pegawai');
        Route::post('/delete', [KategoriPegawaiController::class, 'delete'])->name('delete_kategori_pegawai');
    });

    Route::prefix('shift')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('shift');
        Route::post('/form', [ShiftController::class, 'form'])->name('form_shift');
        Route::post('/store', [ShiftController::class, 'store'])->name('store_shift');
        Route::post('/delete', [ShiftController::class, 'delete'])->name('delete_shift');
    });

    Route::prefix('pekerjaan')->group(function () {
        Route::get('/', [PekerjaanController::class, 'index'])->name('pekerjaan');
        Route::post('/form', [PekerjaanController::class, 'form'])->name('form_pekerjaan');
        Route::post('/store', [PekerjaanController::class, 'store'])->name('store_pekerjaan');
        Route::post('/delete', [PekerjaanController::class, 'delete'])->name('delete_pekerjaan');
        Route::post('/import', [PekerjaanController::class, 'import'])->name('import_pekerjaan');
    });

    Route::prefix('pekerjaan_pegawai')->group(function () {
        Route::get('/', [PekerjaanController::class, 'pekerjaan_pegawai'])->name('pekerjaan_pegawai');
        Route::post('/form', [PekerjaanController::class, 'form_pekerjaan_pegawai'])->name('form_pekerjaan_pegawai');
        Route::post('/store', [PekerjaanController::class, 'store_pekerjaan_pegawai'])->name('store_pekerjaan_pegawai');
        Route::post('/delete', [PekerjaanController::class, 'delete_pekerjaan_pegawai'])->name('delete_pekerjaan_pegawai');
        Route::post('/get_pekerjaan_kategori', [PekerjaanController::class, 'get_pekerjaan_kategori'])->name('get_pekerjaan_kategori');
    });

    Route::prefix('point_target')->group(function () {
        Route::get('/', [PointTargetController::class, 'index'])->name('point_target');
        Route::post('/form', [PointTargetController::class, 'form'])->name('form_point_target');
        Route::post('/store', [PointTargetController::class, 'store'])->name('store_point_target');
    });

    Route::prefix('sebaran_pegawai')->group(function () {
        Route::get('/', [LokasiPegawaiController::class, 'index'])->name('sebaran_pegawai');
    });

    Route::prefix('lokasi_kantor')->group(function () {
        Route::get('/', [LokasiKantorController::class, 'index'])->name('lokasi_kantor');
        Route::post('/form', [LokasiKantorController::class, 'form'])->name('form_lokasi_kantor');
        Route::post('/store', [LokasiKantorController::class, 'store'])->name('store_lokasi_kantor');
        Route::post('/delete', [LokasiKantorController::class, 'delete'])->name('delete_lokasi_kantor');
        Route::get('/get_lokasi_kantor', [LokasiKantorController::class, 'get_lokasi_kantor'])->name('get_lokasi_kantor');
    });

    Route::prefix('kategori_izin')->group(function () {
        Route::get('/', [KategoriIzinController::class, 'index'])->name('kategori_izin');
        Route::post('/form', [KategoriIzinController::class, 'form'])->name('form_kategori_izin');
        Route::post('/store', [KategoriIzinController::class, 'store'])->name('store_kategori_izin');
        Route::post('/delete', [KategoriIzinController::class, 'delete'])->name('delete_kategori_izin');
    });

    Route::prefix('penilaian_kerja')->group(function () {
        Route::get('/', [PekerjaanController::class, 'penilaian_kerja'])->name('penilaian_kerja');
        Route::post('/detail', [PekerjaanController::class, 'detail'])->name('detail_penilaian_kerja');
        Route::post('/dt_detail_penilaian_pekerjaan', [PekerjaanController::class, 'dt_detail_penilaian_pekerjaan'])->name('dt_detail_penilaian_pekerjaan');
        Route::post('/rate_pekerjaan', [PekerjaanController::class, 'rate_pekerjaan'])->name('rate_pekerjaan');
        Route::post('/change_status', [PekerjaanController::class, 'change_status'])->name('change_status_pekerjaan');
        Route::post('/detail_pekerjaan', [PekerjaanController::class, 'detail_pekerjaan'])->name('detail_pekerjaan');
    });

    Route::prefix('presensi')->group(function () {
        Route::get('/', [PresensiController::class, 'index'])->name('presensi');
        Route::post('/detail', [PresensiController::class, 'detail'])->name('detail_presensi');
        Route::post('/dt_detail_presensi', [PresensiController::class, 'dt_detail_presensi'])->name('dt_detail_presensi');
    });

    Route::prefix('perizinan')->group(function () {
        Route::get('/', [PerizinanController::class, 'index'])->name('perizinan');
        Route::get('/riwayat', [PerizinanController::class, 'riwayat_perizinan'])->name('riwayat_perizinan');
        Route::post('/detail', [PerizinanController::class, 'detail'])->name('detail_perizinan');
        Route::post('/change_status', [PerizinanController::class, 'change_status'])->name('change_status_perizinan');
    });

    Route::prefix('bidang')->group(function () {
        Route::get('/', [BidangController::class, 'index'])->name('bidang');
        Route::post('/form', [BidangController::class, 'form'])->name('form_kategori_bidang');
        Route::post('/store', [BidangController::class, 'store'])->name('store_kategori_bidang');
    });
});