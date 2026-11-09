@extends('layouts.app')

@section('title', 'Mahasiswa (REST API)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-2">Mahasiswa (REST API)</h2>
        <p class="text-muted mb-0">
            Halaman ini memanggil endpoint <code>/api/mahasiswa</code> menggunakan JavaScript <code>fetch()</code>.
        </p>
    </div>
    <button type="button" class="btn btn-primary" id="btn-tambah">
        + Tambah Mahasiswa
    </button>
</div>

<div id="alert-box"></div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form id="form-filter" class="row g-3 align-items-end">
            <div class="col-12 col-lg-5">
                <label for="filter-search" class="form-label">Cari mahasiswa</label>
                <input type="search" id="filter-search" class="form-control"
                       placeholder="Cari NIM, nama, prodi, atau email">
            </div>
            <div class="col-6 col-lg-3">
                <label for="filter-sort" class="form-label">Urutkan</label>
                <select id="filter-sort" class="form-select">
                    <option value="nama">Nama</option>
                    <option value="nim">NIM</option>
                    <option value="prodi">Program Studi</option>
                </select>
            </div>
            <div class="col-6 col-lg-2">
                <label for="filter-direction" class="form-label">Arah</label>
                <select id="filter-direction" class="form-select">
                    <option value="asc">A–Z</option>
                    <option value="desc">Z–A</option>
                </select>
            </div>
            <div class="col-12 col-lg-2">
                <button type="submit" class="btn btn-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <p class="text-muted small mb-3" id="info-data">Memuat data...</p>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No.</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th width="190" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-mahasiswa"></tbody>
            </table>
        </div>
        <nav class="mt-3">
            <ul class="pagination mb-0" id="pagination"></ul>
        </nav>
    </div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form-mahasiswa" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="mhs-id">
                @foreach ([
                    'nim' => ['NIM', 'text', true],
                    'nama' => ['Nama Mahasiswa', 'text', true],
                    'prodi' => ['Program Studi', 'text', true],
                    'email' => ['Email', 'email', false],
                    'nohp' => ['Nomor HP', 'text', false],
                ] as $field => [$label, $type, $wajib])
                    <div class="mb-3">
                        <label for="{{ $field }}" class="form-label">
                            {{ $label }} @if ($wajib)<span class="text-danger">*</span>@endif
                        </label>
                        <input type="{{ $type }}" id="{{ $field }}" name="{{ $field }}" class="form-control">
                        <div class="invalid-feedback" data-error-for="{{ $field }}"></div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm mb-0" id="tabel-detail"></table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const API_URL = @json(url('/api/mahasiswa'));
const FIELDS = ['nim', 'nama', 'prodi', 'email', 'nohp'];
let halamanAktif = 1;
const modalForm = new bootstrap.Modal(document.getElementById('modal-form'));
const modalDetail = new bootstrap.Modal(document.getElementById('modal-detail'));

async function panggilApi(url, method = 'GET', body = null) {
    const options = {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    };
    if (body) {
        options.body = JSON.stringify(body);
    }

    const response = await fetch(url, options);
    return {
        ok: response.ok,
        status: response.status,
        data: await response.json(),
    };
}

function escapeHtml(teks) {
    const div = document.createElement('div');
    div.textContent = teks ?? '';
    return div.innerHTML;
}

function tampilkanPesan(pesan, tipe = 'success') {
    document.getElementById('alert-box').innerHTML = `
        <div class="alert alert-${tipe} alert-dismissible fade show" role="alert">
            ${escapeHtml(pesan)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
}

async function muatData(page = 1) {
    halamanAktif = page;
    const params = new URLSearchParams({
        page,
        search: document.getElementById('filter-search').value,
        sort: document.getElementById('filter-sort').value,
        direction: document.getElementById('filter-direction').value,
    });
    const hasil = await panggilApi(`${API_URL}?${params}`);
    if (!hasil.ok) {
        tampilkanPesan('Gagal memuat data mahasiswa.', 'danger');
        return;
    }

    const { data, meta } = hasil.data;
    renderTabel(data, meta);
    renderPagination(meta);
}

function renderTabel(daftar, meta) {
    const tbody = document.getElementById('tabel-mahasiswa');
    document.getElementById('info-data').textContent =
        `Menampilkan ${meta.from ?? 0}–${meta.to ?? 0} dari ${meta.total} mahasiswa.`;

    if (daftar.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>';
        return;
    }

    tbody.innerHTML = daftar.map((mhs, i) => `
        <tr>
            <td>${meta.from + i}</td>
            <td>${escapeHtml(mhs.nim)}</td>
            <td>${escapeHtml(mhs.nama)}</td>
            <td>${escapeHtml(mhs.prodi)}</td>
            <td>${escapeHtml(mhs.email || '-')}</td>
            <td>${escapeHtml(mhs.nohp || '-')}</td>
            <td class="text-center">
                <button class="btn btn-info btn-sm" onclick="lihatDetail(${mhs.id})">Detail</button>
                <button class="btn btn-warning btn-sm" onclick="bukaFormEdit(${mhs.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="hapusData(${mhs.id})">Hapus</button>
            </td>
        </tr>`).join('');
}

function renderPagination(meta) {
    const ul = document.getElementById('pagination');
    ul.innerHTML = '';
    if (meta.last_page <= 1) return;

    for (let page = 1; page <= meta.last_page; page++) {
        ul.innerHTML += `
            <li class="page-item ${page === meta.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="muatData(${page}); return false;">${page}</a>
            </li>`;
    }
}

async function lihatDetail(id) {
    const hasil = await panggilApi(`${API_URL}/${id}`);
    if (!hasil.ok) {
        tampilkanPesan(hasil.data.message ?? 'Data tidak ditemukan.', 'danger');
        return;
    }

    const mhs = hasil.data.data;
    const label = {
        nim: 'NIM',
        nama: 'Nama',
        prodi: 'Program Studi',
        email: 'Email',
        nohp: 'Nomor HP',
        created_at: 'Dibuat',
        updated_at: 'Diubah',
    };
    document.getElementById('tabel-detail').innerHTML = Object.entries(label)
        .map(([key, teks]) => `<tr><th width="140">${teks}</th><td>${escapeHtml(mhs[key] || '-')}</td></tr>`)
        .join('');
    modalDetail.show();
}

function resetForm() {
    document.getElementById('form-mahasiswa').reset();
    document.getElementById('mhs-id').value = '';
    FIELDS.forEach((field) => {
        document.getElementById(field).classList.remove('is-invalid');
        document.querySelector(`[data-error-for="${field}"]`).textContent = '';
    });
}

document.getElementById('btn-tambah').addEventListener('click', () => {
    resetForm();
    document.getElementById('modal-title').textContent = 'Tambah Mahasiswa';
    modalForm.show();
});

async function bukaFormEdit(id) {
    resetForm();
    const hasil = await panggilApi(`${API_URL}/${id}`);
    if (!hasil.ok) {
        tampilkanPesan('Data tidak ditemukan.', 'danger');
        return;
    }

    const mhs = hasil.data.data;
    document.getElementById('mhs-id').value = mhs.id;
    FIELDS.forEach((field) => {
        document.getElementById(field).value = mhs[field] ?? '';
    });
    document.getElementById('modal-title').textContent = 'Edit Mahasiswa';
    modalForm.show();
}

document.getElementById('form-mahasiswa').addEventListener('submit', async (event) => {
    event.preventDefault();
    const id = document.getElementById('mhs-id').value;
    const payload = {};
    FIELDS.forEach((field) => {
        payload[field] = document.getElementById(field).value;
    });

    const tombol = document.getElementById('btn-simpan');
    tombol.disabled = true;

    let hasil;
    try {
        hasil = id
            ? await panggilApi(`${API_URL}/${id}`, 'PUT', payload)
            : await panggilApi(API_URL, 'POST', payload);
    } finally {
        tombol.disabled = false;
    }

    if (hasil.status === 422) {
        FIELDS.forEach((field) => {
            const pesan = hasil.data.errors?.[field]?.[0];
            document.getElementById(field).classList.toggle('is-invalid', !!pesan);
            document.querySelector(`[data-error-for="${field}"]`).textContent = pesan ?? '';
        });
        return;
    }

    if (!hasil.ok) {
        tampilkanPesan(hasil.data.message ?? 'Terjadi kesalahan.', 'danger');
        return;
    }

    modalForm.hide();
    tampilkanPesan(hasil.data.message);
    await muatData(id ? halamanAktif : 1);
});

async function hapusData(id) {
    if (!confirm('Yakin ingin menghapus mahasiswa ini?')) return;

    const hasil = await panggilApi(`${API_URL}/${id}`, 'DELETE');
    tampilkanPesan(hasil.data.message ?? 'Gagal menghapus.', hasil.ok ? 'success' : 'danger');
    await muatData(halamanAktif);
}

document.getElementById('form-filter').addEventListener('submit', (event) => {
    event.preventDefault();
    muatData(1);
});

muatData();
</script>
@endpush
