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

Route::get('/profile', function () {
    return view('profile');
});
Route::get('/home', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});


//user
Route::get('/dashboarduser', function () {
    return view('dashboarduser');
});

Route::get('/absenseuser', function () {
    return view('absenseuser');
});

Route::get('/salaryuser', function () {
    return view('salaryuser');
});

// Route::get('/leaveuser', function () {
//     return view('leaveuser');
// });

Route::get('/leaveuser', function () {
    return view('leaveuser');
});

Route::get('/profileuser', function () {
    return view('profileuser');
});
Route::get('/loginuser', function () {
    return view('loginuser');
});
