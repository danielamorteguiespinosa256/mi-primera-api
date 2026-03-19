<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');