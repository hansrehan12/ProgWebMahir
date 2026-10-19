<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengawasRequest;
use App\Models\Pengawas;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengawasController extends Controller
{
    public function index(): View
    {
        $searchInput = request('search', '');
        $search = trim(is_string($searchInput) ? $searchInput : '');
        $sortOptions = [
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'notelp' => 'Nomor Telepon',
            'email' => 'Email',
        ];
        $sort = request('sort', 'nama');
        $direction = request('direction', 'asc');

        if (! is_string($sort) || ! array_key_exists($sort, $sortOptions)) {
            $sort = 'nama';
        }

        if (! is_string($direction) || ! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $pengawas = Pengawas::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('nama', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhere('notelp', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('pengawas.index', compact('pengawas', 'sortOptions', 'sort', 'direction'));
    }

    public function create(): View
    {
        return view('pengawas.create');
    }

    public function store(PengawasRequest $request): RedirectResponse
    {
        Pengawas::create($request->validated());

        return redirect()->route('pengawas.index')->with('success', 'Data pengawas berhasil ditambahkan.');
    }

    public function show(Pengawas $pengawas): View
    {
        return view('pengawas.show', compact('pengawas'));
    }

    public function edit(Pengawas $pengawas): View
    {
        return view('pengawas.edit', compact('pengawas'));
    }

    public function update(PengawasRequest $request, Pengawas $pengawas): RedirectResponse
    {
        $pengawas->update($request->validated());

        return redirect()->route('pengawas.index')->with('success', 'Data pengawas berhasil diperbarui.');
    }

    public function destroy(Pengawas $pengawas): RedirectResponse
    {
        $pengawas->delete();

        return redirect()->route('pengawas.index')->with('success', 'Data pengawas berhasil dihapus.');
    }
}
