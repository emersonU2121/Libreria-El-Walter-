<?php

use Illuminate\Support\Facades\Route;

Route::get('/menu', function () {
    return view('menu');  // Carga la vista menu.blade.php
});
