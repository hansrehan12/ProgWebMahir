@extends('layouts.app')

@section('title', 'Dosen (REST API)')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-1">Data Dosen (REST API)</h2>
            <p class="text-muted mb-0">Mengakses endpoint <code>/api/dosen</code> dengan Fetch API.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" onclick="muatData()">Muat Data</button>
            <button type="button" class="btn btn-primary" onclick="bukaModalTambah()">+ Tambah Dosen</button>
        </div>
    </div>

    <div id="alert-box"></div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>NIDN</th>
                    <th>Nama Dosen</th>
                    <th>Program Studi</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th width="210" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabel-dosen">
                <tr><td colspan="6" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form-dosen" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Tambah Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="dosen-id">
                @foreach ([
                    'nidn' => ['NIDN', 'text', true],
                    'nama' => ['Nama Dosen', 'text', true],
                    'prodi' => ['Program Studi', 'text', true],
                    'email' => ['Email', 'email', false],
                    'nohp' => ['No. HP', 'text', false],
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
                <h5 class="modal-title">Detail Dosen</h5>
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
const API_URL = @json(url('/api/dosen'));
const FIELDS = ['nidn', 'nama', 'prodi', 'email', 'nohp'];
const modalForm = new bootstrap.Modal(document.getElementById('modal-form'));
const modalDetail = new bootstrap.Modal(document.getElementById('modal-detail'));

function tampilkanAlert(pesan, tipe = 'success') {
    document.getElementById('alert-box').innerHTML = `
        <div class="alert alert-${tipe} alert-dismissible fade show" role="alert">
            ${pesan}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    `;
}

function bersihkanError() {
    FIELDS.forEach(field => {
        document.getElementById(field).classList.remove('is-invalid');
        document.querySelector(`[data-error-for="${field}"]`).textContent = '';
    });
}

function tampilkanError(errors) {
    Object.entries(errors).forEach(([field, pesan]) => {
        const input = document.getElementById(field);
        const feedback = document.querySelector(`[data-error-for="${field}"]`);
        if (input && feedback) {
            input.classList.add('is-invalid');
            feedback.textContent = pesan[0];
        }
    });
}

async function muatData() {
    const tbody = document.getElementById('tabel-dosen');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center">Memuat data...</td></tr>';

    try {
        const response = await fetch(API_URL, { headers: { 'Accept': 'application/json' } });
        const hasil = await response.json();

        if (!hasil.data || hasil.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">Data tidak ditemukan.</td></tr>';
            return;
        }

        tbody.innerHTML = hasil.data.map(dosen => `
            <tr>
                <td>${dosen.nidn}</td>
                <td>${dosen.nama}</td>
                <td>${dosen.prodi}</td>
                <td>${dosen.email || '-'}</td>
                <td>${dosen.nohp || '-'}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info" onclick="lihatDetail(${dosen.id})">Detail</button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalEdit(${dosen.id})">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusData(${dosen.id})">Hapus</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>';
    }
}

function bukaModalTambah() {
    document.getElementById('form-dosen').reset();
    document.getElementById('dosen-id').value = '';
    document.getElementById('modal-title').textContent = 'Tambah Dosen';
    bersihkanError();
    modalForm.show();
}

async function bukaModalEdit(id) {
    bersihkanError();
    try {
        const response = await fetch(`${API_URL}/${id}`, { headers: { 'Accept': 'application/json' } });
        const hasil = await response.json();
        const dosen = hasil.data;

        document.getElementById('dosen-id').value = dosen.id;
        FIELDS.forEach(field => {
            document.getElementById(field).value = dosen[field] ?? '';
        });
        document.getElementById('modal-title').textContent = 'Edit Dosen';
        modalForm.show();
    } catch (error) {
        tampilkanAlert('Gagal mengambil data dosen.', 'danger');
    }
}

async function lihatDetail(id) {
    try {
        const response = await fetch(`${API_URL}/${id}`, { headers: { 'Accept': 'application/json' } });
        const { data: dosen } = await response.json();

        document.getElementById('tabel-detail').innerHTML = `
            <tr><th>NIDN</th><td>${dosen.nidn}</td></tr>
            <tr><th>Nama</th><td>${dosen.nama}</td></tr>
            <tr><th>Prodi</th><td>${dosen.prodi}</td></tr>
            <tr><th>Email</th><td>${dosen.email || '-'}</td></tr>
            <tr><th>No. HP</th><td>${dosen.nohp || '-'}</td></tr>
            <tr><th>Dibuat</th><td>${dosen.created_at || '-'}</td></tr>
        `;
        modalDetail.show();
    } catch (error) {
        tampilkanAlert('Gagal memuat detail dosen.', 'danger');
    }
}

document.getElementById('form-dosen').addEventListener('submit', async (e) => {
    e.preventDefault();
    bersihkanError();

    const id = document.getElementById('dosen-id').value;
    const url = id ? `${API_URL}/${id}` : API_URL;
    const method = id ? 'PUT' : 'POST';

    const payload = {};
    FIELDS.forEach(field => {
        payload[field] = document.getElementById(field).value.trim() || null;
    });

    const btn = document.getElementById('btn-simpan');
    btn.disabled = true;

    try {
        const response = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });
        const hasil = await response.json();

        if (response.status === 422) {
            tampilkanError(hasil.errors || {});
            return;
        }

        if (!response.ok) {
            tampilkanAlert(hasil.message || 'Terjadi kesalahan saat menyimpan data.', 'danger');
            return;
        }

        modalForm.hide();
        tampilkanAlert(hasil.message || 'Data dosen berhasil disimpan.');
        muatData();
    } catch (error) {
        tampilkanAlert('Gagal terhubung ke server.', 'danger');
    } finally {
        btn.disabled = false;
    }
});

async function hapusData(id) {
    if (!confirm('Yakin ingin menghapus data dosen ini?')) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' },
        });
        const hasil = await response.json();

        if (!response.ok) {
            tampilkanAlert(hasil.message || 'Gagal menghapus data.', 'danger');
            return;
        }

        tampilkanAlert(hasil.message || 'Data dosen berhasil dihapus.');
        muatData();
    } catch (error) {
        tampilkanAlert('Gagal terhubung ke server.', 'danger');
    }
}

document.addEventListener('DOMContentLoaded', muatData);
</script>
@endpush