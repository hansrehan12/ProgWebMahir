<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(): View
    {
        $dosens = Dosen::with('mataKuliah')->get();

        return view('dosen.index', compact('dosens'));
    }
}
