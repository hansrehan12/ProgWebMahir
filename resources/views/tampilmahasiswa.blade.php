@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1">Data Mahasiswa</h1>
            <p class="text-secondary mb-0">Daftar mahasiswa yang terdaftar di sistem akademik.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                <i class="bi bi-people me-1"></i>{{ $mahasiswa->count() }} mahasiswa
            </span>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <h2 class="h5 fw-bold mb-1">Semua Mahasiswa</h2>
            <p class="text-secondary small mb-0">Pilih tombol detail untuk melihat informasi lengkap mahasiswa.</p>
        </div>

        @if($mahasiswa->isEmpty())
            <div class="card-body text-center py-5">
                <span class="rounded-circle bg-light text-secondary d-inline-flex align-items-center justify-content-center mb-3"
                      style="width: 64px; height: 64px;">
                    <i class="bi bi-person-x fs-3"></i>
                </span>
                <h3 class="h5 fw-bold">Belum ada data mahasiswa</h3>
                <p class="text-secondary mb-0">Data mahasiswa akan muncul di sini setelah tersedia.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase">No.</th>
                            <th scope="col" class="py-3 text-secondary small text-uppercase">NIM</th>
                            <th scope="col" class="py-3 text-secondary small text-uppercase">Nama</th>
                            <th scope="col" class="py-3 text-secondary small text-uppercase">Program Studi</th>
                            <th scope="col" class="py-3 text-secondary small text-uppercase">Email</th>
                            <th scope="col" class="pe-4 py-3 text-end text-secondary small text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswa as $mhs)
                            <tr>
                                <td class="ps-4 text-secondary">{{ $loop->iteration }}</td>
                                <td><span class="font-monospace small">{{ $mhs->nim }}</span></td>
                                <td class="fw-semibold text-dark">{{ $mhs->nama }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $mhs->prodi }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ $mhs->email ?: 'Belum diisi' }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ url('/detilmahasiswa/' . $mhs->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
