<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROTAS ADM 
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// ROTAS DE USUÁRIO AUTENTICADO
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // AQUI ENTRA A MATRIZ DE EISENHOWER 
    Route::get('/eisenhower', function () {
        return view('eisenhower');
    })->name('eisenhower');

});
