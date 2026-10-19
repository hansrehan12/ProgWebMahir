@extends('layouts.app')

@section('title', 'Data Mata Kuliah')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Mata Kuliah</h3>
    <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>
<div class="table-responsive">
    <table id="mata-kuliah-table" class="table table-hover align-middle">
        <thead class="table-light">
            <tr><th>No</th><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Dosen Pengampu</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach ($mataKuliah as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->kode }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->sks }}</td>
                    <td>{{ $row->dosen?->nama ?? 'Belum ditentukan' }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('mata-kuliah.show', $row) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <a href="{{ route('mata-kuliah.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('mata-kuliah.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function () {
        $('#mata-kuliah-table').DataTable({
            language: {
                emptyTable: 'Belum ada data mata kuliah.',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ data.',
                infoEmpty: 'Menampilkan 0 data.',
                infoFiltered: '(disaring dari _MAX_ total data)',
                lengthMenu: 'Tampilkan _MENU_ data',
                loadingRecords: 'Memuat...',
                processing: 'Memproses...',
                search: 'Cari:',
                zeroRecords: 'Data yang dicari tidak ditemukan.',
                paginate: {
                    first: 'Pertama',
                    last: 'Terakhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                },
                aria: {
                    sortAscending: ': aktifkan untuk mengurutkan kolom secara menaik',
                    sortDescending: ': aktifkan untuk mengurutkan kolom secara menurun'
                }
            }
        });
    });
</script>
@endpush
