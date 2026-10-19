@extends('layouts.app')

@section('content')
<h3 class="mb-3">Jadwal Dosen Mengajar</h3>

<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>NIDN</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Mengajar</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($dosens as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->nidn }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->prodi }}</td>
            <td>
                @forelse ($row->mataKuliah as $ngajar)
                    {{ $ngajar->kode }} {{ $ngajar->nama }}<br>
                @empty
                    <span class="text-muted">Belum ada mata kuliah</span>
                @endforelse
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data dosen.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
