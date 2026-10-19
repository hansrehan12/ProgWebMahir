<?php

namespace App\Http\Controllers;

use App\Http\Requests\DosenRequest;
use App\Models\Dosen;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(): View
    {
        $searchInput = request('search', '');
        $search = trim(is_string($searchInput) ? $searchInput : '');
        $prodi = request('prodi');
        $prodi = is_string($prodi) ? $prodi : null;
        $sortOptions = ['nidn' => 'NIDN', 'nama' => 'Nama', 'prodi' => 'Program Studi'];
        $sort = request('sort', 'nama');
        $direction = request('direction', 'asc');

        if (! is_string($sort) || ! array_key_exists($sort, $sortOptions)) {
            $sort = 'nama';
        }

        if (! is_string($direction) || ! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $dosen = Dosen::query()
            ->with('mataKuliah')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('nidn', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('prodi', 'like', "%{$search}%");
                });
            })
            ->when($prodi, fn ($query) => $query->where('prodi', $prodi))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        $programStudi = Dosen::query()
            ->select('prodi')
            ->distinct()
            ->orderBy('prodi')
            ->pluck('prodi');

        return view('dosen.index', compact('dosen', 'programStudi', 'sortOptions', 'sort', 'direction'));
    }

    public function create(): View
    {
        return view('dosen.create');
    }

    public function store(DosenRequest $request): RedirectResponse
    {
        Dosen::create($request->validated());

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen): View
    {
        $dosen->load('mataKuliah');

        return view('dosen.show', compact('dosen'));
    }

    public function edit(Dosen $dosen): View
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(DosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $dosen->update($request->validated());

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dipindahkan ke Data Terhapus.');
    }

    public function trash(): View
    {
        $dosens = Dosen::onlyTrashed()->orderBy('nama')->get();

        return view('dosen.trash', compact('dosens'));
    }

    public function restore(int $id): RedirectResponse
    {
        $dosen = Dosen::onlyTrashed()->findOrFail($id);
        $dosen->restore();

        return redirect()->route('dosen.trash')->with('success', 'Data dosen berhasil dipulihkan.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $dosen = Dosen::onlyTrashed()->findOrFail($id);
        $dosen->forceDelete();

        return redirect()->route('dosen.trash')->with('success', 'Data dosen berhasil dihapus permanen.');
    }
}
