@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white bg-primary">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="text-white-50 text-uppercase fw-semibold small mb-2">Sistem Informasi Akademik</p>
                    <h1 class="display-6 fw-bold mb-3">Selamat datang di Siakad</h1>
                    <p class="lead mb-0">Akses informasi mahasiswa dan data akademik dengan mudah melalui menu dashboard.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <i class="bi bi-mortarboard-fill" aria-hidden="true" style="font-size: 6rem; opacity: .75;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">Akses Cepat</h2>
            <p class="text-secondary mb-0">Pilih bagian yang ingin Anda buka.</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ url('/tampilmahasiswa') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body p-4">
                    <span class="rounded-3 bg-primary-subtle text-primary p-3 fs-4 d-inline-flex mb-3">
                        <i class="bi bi-people"></i>
                    </span>
                    <h3 class="h5 fw-bold text-dark">Data Mahasiswa</h3>
                    <p class="text-secondary mb-3">Lihat daftar mahasiswa dan buka informasi detailnya.</p>
                    <span class="text-primary fw-semibold">Buka data <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ url('/profil') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body p-4">
                    <span class="rounded-3 bg-success-subtle text-success p-3 fs-4 d-inline-flex mb-3">
                        <i class="bi bi-person-vcard"></i>
                    </span>
                    <h3 class="h5 fw-bold text-dark">Profil</h3>
                    <p class="text-secondary mb-3">Lihat informasi profil yang tersedia di sistem.</p>
                    <span class="text-primary fw-semibold">Lihat profil <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ url('/pendidikan') }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body p-4">
                    <span class="rounded-3 bg-warning-subtle text-warning p-3 fs-4 d-inline-flex mb-3">
                        <i class="bi bi-journal-bookmark"></i>
                    </span>
                    <h3 class="h5 fw-bold text-dark">Pendidikan</h3>
                    <p class="text-secondary mb-3">Buka informasi pendidikan yang tersedia.</p>
                    <span class="text-primary fw-semibold">Lihat pendidikan <i class="bi bi-arrow-right ms-1"></i></span>
                </div>
            </a>
        </div>
    </div>
@endsection
