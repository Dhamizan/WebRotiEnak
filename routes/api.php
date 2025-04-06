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
        'peran' => $request->user()->peran == 1 ? 'admin' : 'pegawai',
        // tambah field lain sesuai kebutuhan
    ]);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/admin/dashboard', [PenggunaController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/karyawan/pending', [PenggunaController::class, 'antreanPengguna']);
    Route::post('/karyawan', [PenggunaController::class, 'tambahKaryawan']);
    Route::put('/karyawan/{id}/fingerprint', [PenggunaController::class, 'membuatSidikJari']);
    Route::get('/karyawan', [PenggunaController::class, 'lihatKaryawan']);
    Route::post('/absensi', [AbsensiController::class, 'rekapAbsensi']);
    Route::post('/logout', [PenggunaController::class, 'keluar']);
    Route::put('/edit-profil', [PenggunaController::class, 'suntingProfil']);
    Route::post('/pengajuan-cuti', [CutiController::class, 'pengajuanCuti']);
    Route::put('/penerimaan-cuti/{id}', [CutiController::class, 'penerimaanCuti'])->middleware('auth:admin');
    Route::get('/gerai', [GeraiController::class, 'index']);
    Route::post('/gerai', [GeraiController::class, 'store']);
    Route::get('/gerai/{id}', [GeraiController::class, 'show']);
    Route::put('/gerai/{id}', [GeraiController::class, 'update']);
    Route::delete('/gerai/{id}', [GeraiController::class, 'destroy']);
    Route::delete('/karyawan/{id}', [PenggunaController::class, 'destroy']);
});

Route::post('/send-verification-email', [PenggunaController::class, 'kirimTautanVerifikasiEmail'])->name('verification.verify');
Route::get('/verify-email', [PenggunaController::class, 'verifikasiEmail'])->name('verify.email')->middleware('signed');
Route::post('/masuk', [PenggunaController::class, 'masuk'])->name('login');
Route::get('/reset-password', [PenggunaController::class, 'halamanResetPassword'])->middleware('signed')->name('reset.password.form');
Route::post('/reset-password-link', [PenggunaController::class, 'kirimTautanPengaturanUlang']);
Route::post('/reset-password', [PenggunaController::class, 'aturUlangKataSandi'])->name('reset.password.submit');
Route::get('/hitung-gaji/{id_pengguna}', [GajiController::class, 'hitungGaji']);
