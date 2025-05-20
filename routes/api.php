<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\GeraiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'email' => $request->user()->email,
        'peran' => $request->user()->peran == 1 ? 'admin' : 'pegawai'
    ]);
});

Route::middleware('auth:api')->post('/keluar', function (Request $request) {
    $user = Auth::user();
    $user->token()->revoke();
    return response()->json(['message' => 'Logout berhasil']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/pegawai/pending', [PenggunaController::class, 'antreanPengguna']);
    Route::get('/pegawai/terakhir', [PenggunaController::class, 'pegawaiTerakhir']);
    Route::post('/pegawai', [PenggunaController::class, 'tambahPegawai']);
    Route::put('/pegawai/{id}/fingerprint', [PenggunaController::class, 'membuatSidikJari']);
    Route::get('/admin', [PenggunaController::class, 'lihatAdmin']);
    Route::get('/pegawai', [PenggunaController::class, 'lihatPegawai']);
    Route::get('/pegawai-profil', [PenggunaController::class, 'lihatPegawaiProfil']);
    Route::get('/pegawai/{id}', [PenggunaController::class, 'rincianPegawai']);
    Route::post('/absensi', [AbsensiController::class, 'rekapAbsensi']);
    Route::get('/absensi/{id}', [AbsensiController::class, 'lihatAbsensi']);
    Route::get('/absensi-pegawai', [AbsensiController::class, 'lihatAbsensiPegawai']);
    Route::post('/sunting/profil', [PenggunaController::class, 'suntingProfil']);
    Route::post('/keluar', [PenggunaController::class, 'keluar']);
    Route::post('/pengajuan-cuti', [CutiController::class, 'pengajuanCuti']);
    Route::get('/lihatcuti-pegawai', [CutiController::class, 'lihatCutiPegawai']);
    Route::put('/penerimaan-cuti/{id}', [CutiController::class, 'penerimaanCuti'])->middleware('auth:admin');
    Route::post('/gerai', [GeraiController::class, 'simpanGerai']);
    Route::get('/gerai', [GeraiController::class, 'LihatGerai']);
    Route::put('/gerai/{id}', [GeraiController::class, 'suntingGerai']);
    Route::delete('/gerai/{id}', [GeraiController::class, 'hapusGerai']);
    Route::delete('/pegawai/{id}', [PenggunaController::class, 'hapusPegawai']);
    Route::get('/gaji/{id}', [GajiController::class, 'lihatGaji']);
    Route::get('/gaji-pegawai', [GajiController::class, 'lihatGajiPegawai']);
    Route::get('/cuti/{id}', [CutiController::class, 'lihatCuti']);
    Route::get('/absensi-today', [AbsensiController::class, 'absensiHariIni']);
    Route::get('/cuti', [CutiController::class, 'lihatSemuaCuti']);
    Route::post('/cuti/penerimaan/{id}', [CutiController::class, 'penerimaanCuti']);
    Route::get('/cuti-pending', [CutiController::class, 'cutiBelumDiproses']);
    Route::put('/tes-route', function () {
        return response()->json(['pesan' => 'Berhasil akses PUT route']);
    });
    
});

Route::post('/kirim-verifikasi-email', [PenggunaController::class, 'kirimTautanVerifikasiEmail'])->name('verification.verify');
Route::get('/verifikasi-email', [PenggunaController::class, 'verifikasiEmail'])->name('verify.email')->middleware('signed');
Route::post('/masuk', [PenggunaController::class, 'masuk'])->name('login');
Route::get('/reset-password', [PenggunaController::class, 'halamanResetPassword'])->middleware('signed')->name('reset.password.form');
Route::post('/reset-password-link', [PenggunaController::class, 'kirimTautanPengaturanUlang']);
Route::post('/reset-password', [PenggunaController::class, 'aturUlangKataSandi']);
Route::get('/hitung-gaji/{id_pengguna}', [GajiController::class, 'hitungGaji']);

Route::middleware('auth:api')->get('/cek-token', function (Request $request) {
    return response()->json($request->user());
});
