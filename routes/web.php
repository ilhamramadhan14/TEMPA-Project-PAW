<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FindingController;

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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/finding-partner', [FindingController::class, 'index'])->name('findings.index');
Route::middleware('auth')->group(function () {
    Route::get('/buat-finding-partner', [FindingController::class, 'create'])->name('findings.create');
    Route::post('/buat-finding-partner', [FindingController::class, 'store'])->name('findings.store');
});