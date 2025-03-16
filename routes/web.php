<?php

use Illuminate\Support\Facades\Route;

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
