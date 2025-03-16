<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenggunaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin/index');
});

Route::get('/employee', function () {
    return view('admin/employee');
});

Route::get('/absense', function () {
    return view('admin/absense');
});

Route::get('/salary', function () {
    return view('admin/salary');
});

Route::get('/leave', function () {
    return view('admin/leave');
});

Route::get('/profile', function () {
    return view('admin/profile');
});

//Route::get('/login', function () {
//    return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
//})->name('login');