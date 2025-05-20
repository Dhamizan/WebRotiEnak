<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin //

Route::get('/admin/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
});

Route::get('/admin/gerai', function () {
    return Inertia::render('Admin/Gerai');
});

Route::get('/admin/pegawai', function () {
    return Inertia::render('Admin/Pegawai');
});

Route::get('/admin/absensi', function () {
    return Inertia::render('Admin/Absensi');
});

Route::get('/admin/gaji', function () {
    return Inertia::render('Admin/Gaji');
});

Route::get('/admin/cuti', function () {
    return Inertia::render('Admin/Cuti');
});

Route::get('/admin/profil', function () {
    return Inertia::render('Admin/Profil');
});

Route::get('/admin/absensi/rincian', function () {
    return Inertia::render('Admin/2/Absensi');
});

Route::get('/admin/gerai/peta', function () {
    return Inertia::render('Admin/2/MapGerai');
});

Route::get('/admin/gaji/rincian', function () {
    return Inertia::render('Admin/2/Gaji');
});

Route::get('/admin/cuti/rincian', function () {
    return Inertia::render('Admin/2/Cuti');
});

Route::get('/admin/pegawai/profil', function () {
    return Inertia::render('Admin/2/Pegawai');
});

// Pegawai //

Route::get('/pegawai/dashboard', function () {
    return Inertia::render('Pegawai/Dashboard');
});

Route::get('/pegawai/absensi', function () {
    return Inertia::render('Pegawai/Absensi');
});

Route::get('/pegawai/gaji', function () {
    return Inertia::render('Pegawai/Gaji');
});

Route::get('/pegawai/cuti', function () {
    return Inertia::render('Pegawai/Cuti');
});

Route::get('/pegawai/profil', function () {
    return Inertia::render('Pegawai/Profil');
});

// Autentikasi //
Route::get('/masuk', function () {
    return Inertia::render('Autentikasi/Masuk');
});

Route::get('/verifikasi-akun', function () {
    return Inertia::render('Autentikasi/kirim_ubahks');
});

Route::get('/verifikasi-email', function () {
    return Inertia::render('Autentikasi/kirim_verifemail');
});

Route::get('/berhasil-kirim-tautan', function () {
    return Inertia::render('Autentikasi/pesan_ubahks');
});

Route::get('/berhasil-verifikasi-email', function () {
    return Inertia::render('Autentikasi/pesan_verifikasi_email');
});

Route::get('/ubah-kata-sandi', function () {
    return Inertia::render('Autentikasi/ubahkatasandi');
});

Route::get('/kata-sandi-baru', function () {
    return Inertia::render('Autentikasi/pesan_berhasil');
});

Route::get('/lupa-kata-sandi', function () {
    return Inertia::render('Autentikasi/kirim_ubahksnon');
});

Route::get('/tes', function () {
    return Inertia::render('Pegawai/naro');
});