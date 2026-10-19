@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0">Data Dosen</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('dosen.trash') }}" class="btn btn-outline-secondary">Data Terhapus</a>
        <a href="{{ route('dosen.create') }}" class="btn btn-primary">Tambah Dosen</a>
    </div>
</div>

<form method="GET" action="{{ route('dosen.index') }}" class="row g-2 align-items-end mb-3">
    <div class="col-md-3">
        <label for="search" class="form-label">Cari dosen</label>
        <input id="search" type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="NIDN, nama, atau prodi">
    </div>
    <div class="col-md-3">
        <label for="prodi" class="form-label">Program Studi</label>
        <select id="prodi" name="prodi" class="form-select">
            <option value="">Semua prodi</option>
            @foreach ($programStudi as $namaProdi)
                <option value="{{ $namaProdi }}" @selected(request('prodi') === $namaProdi)>{{ $namaProdi }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label for="sort" class="form-label">Urutkan berdasarkan</label>
        <select id="sort" name="sort" class="form-select">
            @foreach ($sortOptions as $kolom => $label)
                <option value="{{ $kolom }}" @selected($sort === $kolom)>{{ $label }}</option>
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
        <a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<p class="text-muted">Menampilkan {{ $dosen->firstItem() ?? 0 }}–{{ $dosen->lastItem() ?? 0 }} dari {{ $dosen->total() }} dosen.</p>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>NIDN</th>
                <th>Nama Dosen</th>
                <th>Program Studi</th>
                <th>Mata Kuliah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dosen as $row)
                <tr>
                    <td>{{ $dosen->firstItem() + $loop->index }}</td>
                    <td>{{ $row->nidn }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->prodi }}</td>
                    <td>
                        @forelse ($row->mataKuliah as $mataKuliah)
                            {{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}<br>
                        @empty
                            <span class="text-muted">Belum ada mata kuliah</span>
                        @endforelse
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('dosen.show', $row) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <a href="{{ route('dosen.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('dosen.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dosen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data dosen.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($dosen->hasPages())
    <div class="mt-3">{{ $dosen->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
