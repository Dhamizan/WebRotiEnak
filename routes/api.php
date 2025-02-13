<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/karyawan/pending', [KaryawanController::class, 'getPendingKaryawan']);
    Route::post('/karyawan', [KaryawanController::class, 'store']);
    Route::put('/karyawan/{id}/fingerprint', [KaryawanController::class, 'updateFingerprint']);
    Route::get('/karyawan', [KaryawanController::class, 'data_karyawan']);
    Route::post('/absensi', [KaryawanController::class, 'absensi']);
});

Route::post('/login', [KaryawanController::class, 'login']);