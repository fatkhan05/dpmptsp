<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\OtherController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login',[AuthController::class,'login'])->name('login');

Route::post('notification' , [OtherController::class, 'send_notification'])->name('notification');

Route::get('get_version' , [OtherController::class, 'get_version']);

Route::post('ubah_password_nik' , [OtherController::class, 'ubah_password']);

Route::group(['middleware' => ['mobile']], function(){
    Route::post('profile' , [AuthController::class, 'profile']);
    Route::post('change_password' , [AuthController::class, 'change_password'])->name('change_password');

    Route::post('create_pekerjaan' , [HomeController::class, 'create_pekerjaan']);
    Route::post('pekerjaan_pegawai' , [HomeController::class, 'pekerjaan_pegawai']);
    Route::post('pekerjaan_kategori_pegawai' , [HomeController::class, 'pekerjaan_kategori_pegawai']);
    Route::post('take_pekerjaan' , [HomeController::class, 'take_pekerjaan']);
    Route::post('mulai_pekerjaan' , [HomeController::class, 'mulai_pekerjaan']);
    Route::post('get_pekerjaan' , [HomeController::class, 'get_pekerjaan']);
    Route::post('selesai_pekerjaan' , [HomeController::class, 'selesai_pekerjaan']);
    Route::post('presensi_masuk' , [HomeController::class, 'presensi_masuk']);
    Route::post('presensi_pulang' , [HomeController::class, 'presensi_pulang']);
    Route::post('riwayat_presensi' , [HomeController::class, 'riwayat_presensi']);
    Route::post('kategori_izin' , [HomeController::class, 'kategori_izin']);
    Route::post('pengajuan_izin' , [HomeController::class, 'pengajuan_izin']);
    Route::post('riwayat_pengajuan_izin' , [HomeController::class, 'riwayat_pengajuan_izin']);
    Route::post('lokasi_kantor' , [HomeController::class, 'lokasi_kantor']);
    Route::post('point_target' , [HomeController::class, 'point_target']);
    
    Route::post('get_photo' , [OtherController::class, 'get_photo']);
    Route::post('lokasi_pegawai' , [OtherController::class, 'lokasi_pegawai']);
    Route::post('riwayat_notification' , [OtherController::class, 'riwayat_notification']);
    Route::post('change_photo_profile' , [OtherController::class, 'change_photo_profile']);
});