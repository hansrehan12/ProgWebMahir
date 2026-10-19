@extends('layouts.app')

@section('title', 'Data Pengawas')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0">Data Pengawas</h3>
    <a href="{{ route('pengawas.create') }}" class="btn btn-primary">Tambah Pengawas</a>
</div>

<form method="GET" action="{{ route('pengawas.index') }}" class="row g-2 align-items-end mb-3">
    <div class="col-md-5">
        <label for="search" class="form-label">Cari pengawas</label>
        <input id="search" type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nama, alamat, nomor telepon, atau email">
    </div>
    <div class="col-md-3">
        <label for="sort" class="form-label">Urutkan berdasarkan</label>
        <select id="sort" name="sort" class="form-select">
            @foreach ($sortOptions as $column => $label)
                <option value="{{ $column }}" @selected($sort === $column)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label for="direction" class="form-label">Urutan</label>
        <select id="direction" name="direction" class="form-select">
            <option value="asc" @selected($direction === 'asc')>A–Z</option>
            <option value="desc" @selected($direction === 'desc')>Z–A</option>
        </select>
    </div>
    <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Terapkan</button>
        <a href="{{ route('pengawas.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<p class="text-muted">Menampilkan {{ $pengawas->firstItem() ?? 0 }}–{{ $pengawas->lastItem() ?? 0 }} dari {{ $pengawas->total() }} pengawas.</p>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengawas as $row)
                <tr>
                    <td>{{ $pengawas->firstItem() + $loop->index }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->alamat }}</td>
                    <td>{{ $row->notelp }}</td>
                    <td>{{ $row->email }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('pengawas.show', $row) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <a href="{{ route('pengawas.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('pengawas.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengawas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data pengawas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pengawas->hasPages())
    <div class="mt-3">{{ $pengawas->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
