@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/tampilmahasiswa') }}" class="text-decoration-none">Mahasiswa</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-1">Detail Mahasiswa</h1>
            <p class="text-secondary mb-0">Informasi lengkap mahasiswa yang dipilih.</p>
        </div>
        <a href="{{ url('/tampilmahasiswa') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke daftar
        </a>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="bg-primary px-4 py-4 px-lg-5">
            <div class="d-flex align-items-center gap-3 text-white">
                <span class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center fw-bold fs-3"
                      style="width: 72px; height: 72px;" aria-hidden="true">
                    {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                </span>
                <div>
                    <p class="text-white-50 small text-uppercase fw-semibold mb-1">Mahasiswa</p>
                    <h2 class="h4 fw-bold mb-1">{{ $mahasiswa->nama }}</h2>
                    <span class="badge bg-white text-primary">{{ $mahasiswa->nim }}</span>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-lg-5">
            <h3 class="h6 text-secondary text-uppercase fw-bold mb-3">Informasi Pribadi dan Akademik</h3>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="rounded-3 border bg-light-subtle p-3 h-100">
                        <p class="text-secondary small mb-1"><i class="bi bi-person-vcard me-2"></i>NIM</p>
                        <p class="fw-semibold mb-0">{{ $mahasiswa->nim }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="rounded-3 border bg-light-subtle p-3 h-100">
                        <p class="text-secondary small mb-1"><i class="bi bi-person me-2"></i>Nama Lengkap</p>
                        <p class="fw-semibold mb-0">{{ $mahasiswa->nama }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="rounded-3 border bg-light-subtle p-3 h-100">
                        <p class="text-secondary small mb-1"><i class="bi bi-mortarboard me-2"></i>Program Studi</p>
                        <p class="fw-semibold mb-0">{{ $mahasiswa->prodi }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="rounded-3 border bg-light-subtle p-3 h-100">
                        <p class="text-secondary small mb-1"><i class="bi bi-envelope me-2"></i>Email</p>
                        <p class="fw-semibold mb-0">{{ $mahasiswa->email ?: 'Belum diisi' }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="rounded-3 border bg-light-subtle p-3 h-100">
                        <p class="text-secondary small mb-1"><i class="bi bi-telephone me-2"></i>Nomor HP</p>
                        <p class="fw-semibold mb-0">{{ $mahasiswa->nohp ?: 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection