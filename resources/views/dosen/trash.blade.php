@extends('layouts.app')

@section('title', 'Data Dosen Terhapus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Dosen Terhapus</h3>
    <a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary">Kembali ke Data Dosen</a>
</div>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr><th>NIDN</th><th>Nama Dosen</th><th>Program Studi</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($dosens as $dosen)
                <tr>
                    <td>{{ $dosen->nidn }}</td>
                    <td>{{ $dosen->nama }}</td>
                    <td>{{ $dosen->prodi }}</td>
                    <td class="text-nowrap">
                        <form action="{{ route('dosen.restore', $dosen->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success" type="submit">Pulihkan</button>
                        </form>
                        <form action="{{ route('dosen.forceDelete', $dosen->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dosen ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">Tidak ada data dosen yang terhapus.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
