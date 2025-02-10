<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;

Route::get('/karyawan/pending', [KaryawanController::class, 'getPendingKaryawan']);
Route::post('/karyawan', [KaryawanController::class, 'store']);
Route::put('/karyawan/{id}/fingerprint', [KaryawanController::class, 'updateFingerprint']);
Route::get('/test', function () {return response()->json(['message' => 'API is working']);});
Route::post('/login-karyawan', [KaryawanController::class, 'login_user']);
Route::post('login-admin', [KaryawanController::class, 'login_admin']);
Route::get('/karyawan', [KaryawanController::class, 'data_karyawan']);
Route::post('/absensi', [KaryawanController::class, 'absensi']);
