<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PengawasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/salam', function() {
    return 'salam kenal';

});

Route::get('hello', 'App\http\Controllers\HelloController@index');
Route::get('biodata', 'App\http\Controllers\HelloController@biodata');

Route::get('/mahasiswa/{nama}', function ($nama){
    return 'Nama mahasiswa: ' . $nama;

});
Route::get('profil', 'App\Http\Controllers\MahasiswaController@profil');
Route::get('pendidikan', 'App\Http\Controllers\MahasiswaController@pendidikan');

Route::get('/mahasiswa', 'App\http\Controllers\MahasiswaController@index');
Route::get('/tampilmahasiswa', 'App\http\Controllers\MahasiswaController@tampil');

Route::get('/tampilmahasiswa', 'App\Http\Controllers\MahasiswaController@tampil');
Route::get('/detilmahasiswa/{id}', 'App\Http\Controllers\MahasiswaController@detil');

Route::get('/datamahasiswa', 'App\Http\Controllers\MahasiswaController@data');

Route::get('/getDosen', [DosenController::class, 'index']);
Route::get('dosen/trash', [DosenController::class, 'trash'])->name('dosen.trash');
Route::post('dosen/{id}/restore', [DosenController::class, 'restore'])->name('dosen.restore');
Route::delete('dosen/{id}/force-delete', [DosenController::class, 'forceDelete'])->name('dosen.forceDelete');
Route::resource('dosen', DosenController::class);
Route::resource('mata-kuliah', MataKuliahController::class);
Route::resource('pengawas', PengawasController::class)->parameters(['pengawas' => 'pengawas']);