<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index()
    {
        return view('hello');
    }
    public function biodata()
    {
        $nim = "2411501550";
        $nama = "Raihan Adi Wijiyanto";
        $alamat = "Jakarta";
        return view ('hello', compact('nim','nama','alamat'));
    }
    
    //
}
