<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/aprendices', function () {
    return view ('aprendices'); 
});
