<?php

namespace App\Http\Controllers;

use App\Http\Requests\MataKuliahRequest;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MataKuliahController extends Controller
{
    public function index(): View
    {
        $mataKuliah = MataKuliah::query()->with('dosen')->orderBy('kode')->get();

        return view('mata-kuliah.index', compact('mataKuliah'));
    }

    public function create(): View
    {
        $dosens = Dosen::query()->orderBy('nama')->get();

        return view('mata-kuliah.create', compact('dosens'));
    }

    public function store(MataKuliahRequest $request): RedirectResponse
    {
        MataKuliah::create($request->validated());

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $mataKuliah): View
    {
        $mataKuliah->load('dosen');

        return view('mata-kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah): View
    {
        $dosens = Dosen::query()->orderBy('nama')->get();

        return view('mata-kuliah.edit', compact('mataKuliah', 'dosens'));
    }

    public function update(MataKuliahRequest $request, MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->update($request->validated());

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah): RedirectResponse
    {
        $mataKuliah->delete();

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
