<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login', ['tab' => 'masuk']);
})->name('login');

Route::get('/signup', function () {
    return view('login', ['tab' => 'daftar']);
})->name('signup');

Route::get('/', function () {
    return view('home');
})->name('home');