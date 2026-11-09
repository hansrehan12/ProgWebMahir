<?php

use App\Http\Controllers\Api\MahasiswaApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('mahasiswa', MahasiswaApiController::class)
    ->names('api.mahasiswa');