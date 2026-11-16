<?php

use App\Http\Controllers\Api\MahasiswaApiController;
use App\Http\Controllers\Api\DosenApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('mahasiswa', MahasiswaApiController::class)
    ->names('api.mahasiswa');
Route::apiResource('dosen', DosenApiController::class)
    ->names('api.dosen');

    