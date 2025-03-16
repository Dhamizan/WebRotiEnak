<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/karyawan/pending', [PenggunaController::class, 'antreanPengguna']);
    Route::post('/karyawan', [PenggunaController::class, 'tambahKaryawan']);
    Route::put('/karyawan/{id}/fingerprint', [PenggunaController::class, 'membuatSidikJari']);
    Route::get('/karyawan', [PenggunaController::class, 'lihatKaryawan']);
    Route::post('/absensi', [AbsensiController::class, 'rekapAbsensi']);
});

Route::post('/send-verification-email', [PenggunaController::class, 'kirimTautanVerifikasiEmail'])->name('verification.verify');
Route::get('/verify-email', [PenggunaController::class, 'verifikasiEmail'])->name('verify.email')->middleware('signed');
Route::post('/login', [PenggunaController::class, 'masuk'])->name('login');
Route::post('/logout', [PenggunaController::class, 'keluar']);
Route::get('/reset-password', [PenggunaController::class, 'halamanResetPassword'])->middleware('signed')->name('reset.password');
Route::post('/reset-password-link', [PenggunaController::class, 'kirimTautanPengaturanUlang']);
Route::post('/reset-password', [PenggunaController::class, 'aturUlangKataSandi'])->middleware('signed')->name('reset.password');
