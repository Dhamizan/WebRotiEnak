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
    return view('index');
});

Route::get('/dashboard', function () {
    return view('index');
});

Route::get('/employee', function () {
    return view('employee');
});

Route::get('/absense', function () {
    return view('absense');
});

Route::get('/salary', function () {
    return view('salary');
});

Route::get('/leave', function () {
    return view('leave');
});

//Route::get('/login', function () {
//    return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
//})->name('login');