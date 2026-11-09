# Tutorial Lengkap: REST API Mahasiswa (Salin–Tempel)

Disesuaikan untuk proyek **ProgWebMahir** (Laravel 12 + layout `layouts.app`).

Ikuti **urut dari atas ke bawah**. Setiap langkah: buat/ganti file → jalankan perintah cek → lanjut.

---

## Persiapan (sekali saja)

Pastikan server & data mahasiswa ada:

```powershell
# di folder proyek
php artisan serve
```

Seed data mahasiswa (opsional, biar tabel tidak kosong):

```powershell
php artisan db:seed --class=MahasiswaSeeder
```

Endpoint nanti: `http://127.0.0.1:8000/api/mahasiswa`  
Halaman client nanti: `http://127.0.0.1:8000/mahasiswa-api`

---

## Langkah 1 — Aktifkan routing API

### 1a. Ganti isi `bootstrap/app.php`

> Catatan: proyek kamu **tidak punya** `RoleMiddleware`, jadi versi di bawah sudah dibersihkan.

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Semua error pada URL /api/* dikembalikan sebagai JSON
        $exceptions->shouldRenderJsonWhen(
            fn ($request) => $request->is('api/*') || $request->expectsJson()
        );

        // Pesan 404 rapi untuk API
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }
        });
    })->create();
```

### 1b. Buat file baru `routes/api.php`

Isi sementara:

```php
<?php

use Illuminate\Support\Facades\Route;

// Route API akan ditambahkan di Langkah 5
```

### Cek

```powershell
php artisan route:list --path=api
```

Belum ada endpoint mahasiswa = normal. Yang penting tidak error.

---

## Langkah 2 — Form Request (validasi)

```powershell
php artisan make:request MahasiswaRequest
```

Ganti isi `app/Http/Requests/MahasiswaRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat update, model mahasiswa ada di route parameter {mahasiswa}
        $mahasiswa = $this->route('mahasiswa');

        return [
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'nohp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nim.max' => 'NIM maksimal 20 karakter.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 100 karakter.',
            'prodi.required' => 'Program studi wajib diisi.',
            'prodi.max' => 'Program studi maksimal 100 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'nohp.max' => 'Nomor HP maksimal 20 karakter.',
        ];
    }
}
```

---

## Langkah 3 — API Resource (format JSON)

```powershell
php artisan make:resource MahasiswaResource
```

Ganti isi `app/Http/Resources/MahasiswaResource.php`:

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nim' => $this->nim,
            'nama' => $this->nama,
            'prodi' => $this->prodi,
            'email' => $this->email,
            'nohp' => $this->nohp,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
```

---

## Langkah 4 — API Controller

```powershell
php artisan make:controller Api/MahasiswaApiController --api --model=Mahasiswa
```

Ganti isi `app/Http/Controllers/Api/MahasiswaApiController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    /**
     * GET /api/mahasiswa
     * Query: search, prodi, sort, direction, per_page, page
     */
    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), ['nim', 'nama', 'prodi'], true)
            ? $request->query('sort')
            : 'nama';

        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $perPage = min(max((int) $request->query('per_page', 10), 1), 100);

        $mahasiswa = Mahasiswa::query()
            ->when(trim((string) $request->query('search')), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nim', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('prodi', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->query('prodi'), fn ($query, $prodi) => $query->where('prodi', $prodi))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return MahasiswaResource::collection($mahasiswa);
    }

    /**
     * POST /api/mahasiswa
     */
    public function store(MahasiswaRequest $request): JsonResponse
    {
        $mahasiswa = Mahasiswa::create($request->validated());

        return (new MahasiswaResource($mahasiswa))
            ->additional(['message' => 'Data mahasiswa berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/mahasiswa/{mahasiswa}
     */
    public function show(Mahasiswa $mahasiswa): MahasiswaResource
    {
        return new MahasiswaResource($mahasiswa);
    }

    /**
     * PUT/PATCH /api/mahasiswa/{mahasiswa}
     */
    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa): MahasiswaResource
    {
        $mahasiswa->update($request->validated());

        return (new MahasiswaResource($mahasiswa))
            ->additional(['message' => 'Data mahasiswa berhasil diperbarui.']);
    }

    /**
     * DELETE /api/mahasiswa/{mahasiswa}
     */
    public function destroy(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();

        return response()->json([
            'message' => 'Data mahasiswa berhasil dihapus.',
        ]);
    }
}
```

Pastikan model `Mahasiswa` punya `$fillable` (di proyekmu sudah ada):

```php
protected $fillable = [
    'nim',
    'nama',
    'prodi',
    'nohp',
    'email',
];
```

---

## Langkah 5 — Daftarkan route API

Ganti isi `routes/api.php`:

```php
<?php

use App\Http\Controllers\Api\MahasiswaApiController;
use Illuminate\Support\Facades\Route;

/*
| Semua route di file ini otomatis dapat prefix "/api"
| dan middleware group "api" (tanpa session & CSRF).
|
| ->names('api.mahasiswa') penting supaya tidak bentrok
| dengan nama route web mahasiswa (kalau ada).
*/
Route::apiResource('mahasiswa', MahasiswaApiController::class)
    ->names('api.mahasiswa');
```

### Cek

```powershell
php artisan route:list --path=api
```

Harus muncul:

```
GET|HEAD   api/mahasiswa
POST       api/mahasiswa
GET|HEAD   api/mahasiswa/{mahasiswa}
PUT|PATCH  api/mahasiswa/{mahasiswa}
DELETE     api/mahasiswa/{mahasiswa}
```

### Uji cepat di browser / PowerShell

```powershell
Invoke-RestMethod http://127.0.0.1:8000/api/mahasiswa
```

Atau buka: http://127.0.0.1:8000/api/mahasiswa

Harus dapat JSON: `data`, `links`, `meta`.

Tambah data (PowerShell):

```powershell
Invoke-RestMethod -Method POST -Uri http://127.0.0.1:8000/api/mahasiswa `
  -ContentType "application/json" `
  -Headers @{ Accept = "application/json" } `
  -Body '{"nim":"DEMO001","nama":"Demo API","prodi":"Sistem Informasi","email":"demo@example.com","nohp":"08123456789"}'
```

---

## Langkah 6 — Route halaman client

Tambahkan di **akhir** `routes/web.php`:

```php
// Halaman client yang memakai REST API /api/mahasiswa
Route::view('/mahasiswa-api', 'mahasiswa-api.index')->name('mahasiswa-api.index');
```

---

## Langkah 7 — Tambah menu di sidebar

Buka `resources/views/layouts/app.blade.php`.  
Di bawah menu **Mahasiswa**, tambahkan item baru:

```blade
<li>
    <a href="{{ route('mahasiswa-api.index') }}" class="nav-link {{ request()->is('mahasiswa-api*') ? 'active' : '' }}">
        <i class="bi bi-cloud-arrow-down me-2"></i> Mahasiswa (REST API)
    </a>
</li>
```

Contoh posisi (setelah menu Mahasiswa lama):

```blade
<li>
    <a href="{{ url('/tampilmahasiswa') }}" class="nav-link {{ request()->is('tampilmahasiswa*') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> Mahasiswa
    </a>
</li>
<li>
    <a href="{{ route('mahasiswa-api.index') }}" class="nav-link {{ request()->is('mahasiswa-api*') ? 'active' : '' }}">
        <i class="bi bi-cloud-arrow-down me-2"></i> Mahasiswa (REST API)
    </a>
</li>
```

---

## Langkah 8 + 9 — Halaman client (HTML + JavaScript)

Buat folder & file:

`resources/views/mahasiswa-api/index.blade.php`

Isi **penuh** (HTML + JS sekaligus):

```blade
@extends('layouts.app')

@section('title', 'Mahasiswa (REST API)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-2">Mahasiswa (REST API)</h2>
        <p class="text-muted mb-0">
            Halaman ini tidak mengambil data dari controller Blade, tetapi memanggil
            endpoint <code>/api/mahasiswa</code> memakai JavaScript <code>fetch()</code>.
        </p>
    </div>
    <button type="button" class="btn btn-primary" id="btn-tambah">
        + Tambah Mahasiswa
    </button>
</div>

{{-- Tempat pesan sukses / gagal --}}
<div id="alert-box"></div>

{{-- Filter --}}
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

{{-- Tabel data --}}
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

{{-- Modal form tambah / edit --}}
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

{{-- Modal detail --}}
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
const API_URL = "{{ url('/api/mahasiswa') }}";
const FIELDS = ['nim', 'nama', 'prodi', 'email', 'nohp'];
let halamanAktif = 1;
const modalForm = new bootstrap.Modal(document.getElementById('modal-form'));
const modalDetail = new bootstrap.Modal(document.getElementById('modal-detail'));

async function panggilApi(url, method = 'GET', body = null) {
    const options = {
        method: method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    };
    if (body) {
        options.body = JSON.stringify(body);
    }
    const response = await fetch(url, options);
    const data = await response.json();
    return { ok: response.ok, status: response.status, data: data };
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
        page: page,
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
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>`;
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
    for (let p = 1; p <= meta.last_page; p++) {
        ul.innerHTML += `
            <li class="page-item ${p === meta.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="muatData(${p}); return false;">${p}</a>
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
    FIELDS.forEach(f => {
        document.getElementById(f).classList.remove('is-invalid');
        document.querySelector(`[data-error-for="${f}"]`).textContent = '';
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
    FIELDS.forEach(f => document.getElementById(f).value = mhs[f] ?? '');
    document.getElementById('modal-title').textContent = 'Edit Mahasiswa';
    modalForm.show();
}

document.getElementById('form-mahasiswa').addEventListener('submit', async (event) => {
    event.preventDefault();
    const id = document.getElementById('mhs-id').value;
    const payload = {};
    FIELDS.forEach(f => payload[f] = document.getElementById(f).value);

    const tombol = document.getElementById('btn-simpan');
    tombol.disabled = true;

    const hasil = id
        ? await panggilApi(`${API_URL}/${id}`, 'PUT', payload)
        : await panggilApi(API_URL, 'POST', payload);

    tombol.disabled = false;

    if (hasil.status === 422) {
        FIELDS.forEach(f => {
            const pesan = hasil.data.errors?.[f]?.[0];
            document.getElementById(f).classList.toggle('is-invalid', !!pesan);
            document.querySelector(`[data-error-for="${f}"]`).textContent = pesan ?? '';
        });
        return;
    }

    if (!hasil.ok) {
        tampilkanPesan(hasil.data.message ?? 'Terjadi kesalahan.', 'danger');
        return;
    }

    modalForm.hide();
    tampilkanPesan(hasil.data.message);
    muatData(id ? halamanAktif : 1);
});

async function hapusData(id) {
    if (!confirm('Yakin ingin menghapus mahasiswa ini?')) return;
    const hasil = await panggilApi(`${API_URL}/${id}`, 'DELETE');
    tampilkanPesan(hasil.data.message ?? 'Gagal menghapus.', hasil.ok ? 'success' : 'danger');
    muatData(halamanAktif);
}

document.getElementById('form-filter').addEventListener('submit', (event) => {
    event.preventDefault();
    muatData(1);
});

muatData();
</script>
@endpush
```

### Cek di browser

1. `php artisan serve`
2. Buka http://127.0.0.1:8000/mahasiswa-api
3. Coba: cari, sort, tambah, edit, hapus, detail
4. Buka DevTools (F12) → Network → Fetch/XHR

---

## Langkah 10 (opsional) — Feature test

```powershell
php artisan make:test MahasiswaApiTest
```

Ganti isi `tests/Feature/MahasiswaApiTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_mengembalikan_daftar_terpaginasi(): void
    {
        Mahasiswa::factory()->count(15)->create();

        $this->getJson('/api/mahasiswa?per_page=10')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 15)
            ->assertJsonStructure([
                'data' => [['id', 'nim', 'nama', 'prodi', 'email', 'nohp']],
                'links',
                'meta',
            ]);
    }

    public function test_index_bisa_mencari(): void
    {
        Mahasiswa::factory()->create(['nama' => 'Budi Santoso']);
        Mahasiswa::factory()->create(['nama' => 'Siti Aminah']);

        $this->getJson('/api/mahasiswa?search=Budi')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Budi Santoso');
    }

    public function test_store_menyimpan_data_dan_mengembalikan_201(): void
    {
        $payload = [
            'nim' => '2401001',
            'nama' => 'Andi',
            'prodi' => 'Sistem Informasi',
            'email' => 'andi@example.com',
            'nohp' => '0812',
        ];

        $this->postJson('/api/mahasiswa', $payload)
            ->assertCreated()
            ->assertJsonPath('data.nim', '2401001')
            ->assertJsonPath('message', 'Data mahasiswa berhasil ditambahkan.');

        $this->assertDatabaseHas('mahasiswa', ['nim' => '2401001']);
    }

    public function test_store_validasi_gagal_mengembalikan_422(): void
    {
        Mahasiswa::factory()->create(['nim' => '2401001']);

        $this->postJson('/api/mahasiswa', [
            'nim' => '2401001',
            'email' => 'bukan-email',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nim', 'nama', 'prodi', 'email']);
    }

    public function test_show_mengembalikan_satu_data(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->getJson("/api/mahasiswa/{$mhs->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $mhs->id);
    }

    public function test_show_id_tidak_ada_mengembalikan_404_json(): void
    {
        $this->get('/api/mahasiswa/9999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Data tidak ditemukan.');
    }

    public function test_update_mengubah_data_dan_nim_sendiri_tidak_dianggap_duplikat(): void
    {
        $mhs = Mahasiswa::factory()->create(['nim' => '2401001']);

        $this->putJson("/api/mahasiswa/{$mhs->id}", [
            'nim' => '2401001',
            'nama' => 'Nama Baru',
            'prodi' => 'Teknik Informatika',
        ])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Nama Baru');
    }

    public function test_destroy_menghapus_data(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->deleteJson("/api/mahasiswa/{$mhs->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Data mahasiswa berhasil dihapus.');

        $this->assertDatabaseMissing('mahasiswa', ['id' => $mhs->id]);
    }

    public function test_halaman_client_bisa_dibuka(): void
    {
        $this->get('/mahasiswa-api')
            ->assertOk()
            ->assertSee('Mahasiswa (REST API)');
    }
}
```

Jalankan:

```powershell
php artisan test --filter=MahasiswaApiTest
```

---

## Checklist file yang harus ada

| File | Status |
|------|--------|
| `bootstrap/app.php` (ada `api:` + JSON exception) | |
| `routes/api.php` | |
| `app/Http/Requests/MahasiswaRequest.php` | |
| `app/Http/Resources/MahasiswaResource.php` | |
| `app/Http/Controllers/Api/MahasiswaApiController.php` | |
| `routes/web.php` (route `/mahasiswa-api`) | |
| `resources/views/layouts/app.blade.php` (menu baru) | |
| `resources/views/mahasiswa-api/index.blade.php` | |
| `tests/Feature/MahasiswaApiTest.php` (opsional) | |

---

## Troubleshooting cepat

| Masalah | Solusi |
|---------|--------|
| `Target class [MahasiswaApiController] does not exist` | Cek namespace folder `Api` & nama file |
| `Route [mahasiswa-api.index] not defined` | Belum tambah route di `web.php` |
| API balas HTML, bukan JSON | Pastikan header `Accept: application/json` |
| POST dari browser kena CSRF | API route **tidak** pakai CSRF; pastikan request ke `/api/...` |
| Tabel kosong | `php artisan db:seed --class=MahasiswaSeeder` |
| `could not find driver` (sqlite) | Aktifkan `extension=pdo_sqlite` & `extension=sqlite3` di `php.ini` |

---

## Latihan tambahan (dari PDF, opsional)

1. Dropdown filter **Program Studi** di client (`?prodi=...`)
2. Endpoint `GET /api/mahasiswa-prodi` (prodi unik + jumlah)
3. REST API **Dosen** dengan pola sama
4. Lindungi API dengan **Sanctum**
5. Pagination pakai `links.prev` / `links.next`

---

Selesai. Kerjakan dari Langkah 1. Kalau mentok di langkah tertentu, bilang nomor langkah + error-nya.
