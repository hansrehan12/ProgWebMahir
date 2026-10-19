<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index()
    {
        // biarkan isi index() punyamu yang lama
    }

    public function tampil()
    {
        // ambil data mahasiswa
        $mahasiswa = Mahasiswa::all();
        return view('tampilmahasiswa', compact('mahasiswa'));
    }

    public function detil($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('detilmahasiswa', compact('mahasiswa'));
    }

    // fungsi Query Builder (dipakai di langkah 18)
    public function data()
    {
        $mahasiswa = DB::table('mahasiswa')
            ->select('id', 'nim', 'nama', 'prodi', 'email', 'nohp')
            ->orderBy('nama', 'asc')
            ->get();

        return view('tampilmahasiswa', compact('mahasiswa'));
    }
}