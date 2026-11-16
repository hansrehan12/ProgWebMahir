# Materi REST API - Data Dosen (Laravel 13)

> **Penjelasan:** Modul ini disesuaikan strukturnya agar mirip dengan materi SIAKAD (Mahasiswa) sebelumnya, lengkap dengan perancangan HTTP Method, Endpoint, serta menggunakan struktur field `nidn`, `nama`, dan `prodi`.

---

## Bagian 1: Perancangan Endpoint REST API

### 1.1 Daftar Endpoint (HTTP Method)

Base URL saat pengembangan: `http://127.0.0.1:8000`. Semua endpoint diawali `/api`.

| Method | Endpoint | Keterangan | Status Sukses |
|---|---|---|---|
| GET | `/api/dosen` | Mengambil daftar dosen | 200 OK |
| POST | `/api/dosen` | Menambahkan data dosen baru | 201 Created |
| GET | `/api/dosen/{id}` | Menampilkan detail satu dosen | 200 OK |
| PUT / PATCH | `/api/dosen/{id}` | Mengubah/memperbarui data dosen | 200 OK |
| DELETE | `/api/dosen/{id}` | Menghapus data dosen | 200 OK |

### 1.2 Format Request Body (POST dan PUT)

```json
{
  "nidn": "0412345678",
  "nama": "Budi Santoso",
  "prodi": "Informatika",
  "email": "budi@kampus.id",
  "nohp": "081234567890"
}
```

Aturan field:

| Field | Aturan |
|---|---|
| `nidn` | wajib, maks 20, unik |
| `nama` | wajib, maks 100 |
| `prodi` | wajib, maks 100 |
| `email` | opsional, format email |
| `nohp` | opsional, maks 20 |

---

## Bagian 2: Membangun REST API (Server)

### Langkah 1: Pembuatan Tabel & Model

Jalankan perintah di terminal:

```bash
php artisan make:model Dosen -m
```

Buka file migration `database/migrations/..._create_dosens_table.php` dan isi dengan:

```php
Schema::create('dosen', function (Blueprint $table) {
    $table->id();
    $table->string('nidn', 20)->unique();
    $table->string('nama', 100);
    $table->string('prodi', 100);
    $table->string('email', 100)->nullable();
    $table->string('nohp', 20)->nullable();
    $table->timestamps();
});
```

### Langkah 2: Membuat Form Request (Validasi)

Jalankan:

```bash
php artisan make:request DosenRequest
```

Lalu ubah isinya (`app/Http/Requests/DosenRequest.php`):

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dosen = $this->route('dosen');

        return [
            'nidn' => [
                'required', 'string', 'max:20',
                Rule::unique('dosen', 'nidn')->ignore($dosen?->id)
            ],
            'nama'  => ['required', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'nohp'  => ['nullable', 'string', 'max:20'],
        ];
    }
}
```

### Langkah 3: Membuat API Resource

Jalankan:

```bash
php artisan make:resource DosenResource
```

Atur format JSON (`app/Http/Resources/DosenResource.php`):

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DosenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nidn'       => $this->nidn,
            'nama'       => $this->nama,
            'prodi'      => $this->prodi,
            'email'      => $this->email,
            'nohp'       => $this->nohp,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
```

### Langkah 4: Membuat API Controller

Jalankan:

```bash
php artisan make:controller Api/DosenApiController --api --model=Dosen
```

Isi controller (`app/Http/Controllers/Api/DosenApiController.php`):

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Http\Requests\DosenRequest;
use App\Http\Resources\DosenResource;

class DosenApiController extends Controller
{
    // GET /api/dosen
    public function index()
    {
        return DosenResource::collection(Dosen::paginate(10));
    }

    // POST /api/dosen
    public function store(DosenRequest $request)
    {
        $dosen = Dosen::create($request->validated());

        return (new DosenResource($dosen))
            ->additional(['message' => 'Data dosen berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/dosen/{id}
    public function show(Dosen $dosen)
    {
        return new DosenResource($dosen);
    }

    // PUT/PATCH /api/dosen/{id}
    public function update(DosenRequest $request, Dosen $dosen)
    {
        $dosen->update($request->validated());

        return (new DosenResource($dosen))
            ->additional(['message' => 'Data dosen berhasil diperbarui.']);
    }

    // DELETE /api/dosen/{id}
    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return response()->json(['message' => 'Data dosen berhasil dihapus.']);
    }
}
```

### Langkah 5: Mendaftarkan Route

Buka `routes/api.php`:

```php
use App\Http\Controllers\Api\DosenApiController;

Route::apiResource('dosen', DosenApiController::class)->names('api.dosen');
```

Buka `routes/web.php` (untuk halaman client):

```php
Route::view('/dosen-api', 'dosen-api.index')->name('dosen-api.index');
```

---

## Bagian 3: Tampilan Dasar Client (Fetch API)

Buat file `resources/views/dosen-api/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Dosen (REST API)')

@section('content')
<div class="container py-4">
    <h2>Data Dosen (REST API)</h2>
    <button class="btn btn-primary mb-3" onclick="muatData()">Muat Data Dosen</button>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>NIDN</th>
                <th>Nama Dosen</th>
                <th>Program Studi</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel-dosen">
            <tr><td colspan="5" class="text-center">Silakan klik tombol muat data.</td></tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const API_URL = "{{ url('/api/dosen') }}";

async function muatData() {
    const tbody = document.getElementById('tabel-dosen');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Memuat data...</td></tr>';

    try {
        const response = await fetch(API_URL, {
            headers: { 'Accept': 'application/json' }
        });
        const hasil = await response.json();

        if (hasil.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Data tidak ditemukan.</td></tr>';
            return;
        }

        tbody.innerHTML = hasil.data.map(dosen => `
            <tr>
                <td>${dosen.nidn}</td>
                <td>${dosen.nama}</td>
                <td>${dosen.prodi}</td>
                <td>${dosen.email || '-'}</td>
                <td>
                    <!-- Tombol aksi akan ditambahkan oleh mahasiswa -->
                    <button class="btn btn-sm btn-info">Detail</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>';
    }
}
</script>
@endpush
@endsection
```

---

## Bagian 4: Penerapan di Client (Tugas / Latihan Mahasiswa)

> 📋 **Instruksi Tugas Mandiri Front-End:**
> Bagian ini merupakan latihan mandiri untuk mengimplementasikan antarmuka secara utuh (Full CRUD) menggunakan HTML dan JavaScript Fetch API. Silakan kembangkan kerangka kode HTML di Bagian 3 dengan mengikuti panduan di bawah ini.

### 1. Merancang Form Tambah & Edit Data (HTML)

- [ ] Tambahkan sebuah **Modal Bootstrap** (atau form HTML biasa) ke dalam file `index.blade.php`.
- [ ] Di dalam modal tersebut, buat input form untuk masing-masing field (`nidn`, `nama`, `prodi`, `email`, dan `nohp`).
- [ ] Tambahkan satu **input tersembunyi (hidden input)** untuk menyimpan `id` dosen.
  - Jika ID terisi → mode **Edit (PUT)**.
  - Jika ID kosong → mode **Tambah (POST)**.
- [ ] Siapkan elemen `<div class="invalid-feedback">` di bawah tiap input untuk menampilkan pesan error validasi (422) dari server nantinya.

### 2. Mengimplementasikan Fungsi Simpan (JavaScript POST/PUT)

- [ ] Tangkap event submit dari form menggunakan JavaScript (`addEventListener('submit', ...)`).
- [ ] Ambil semua nilai dari input form dan susun menjadi objek data (JSON).
- [ ] Gunakan `fetch()` untuk mengirim data ke endpoint `/api/dosen`:
  - Gunakan method **POST** jika ID dosen kosong.
  - Gunakan method **PUT** (ke URL `/api/dosen/{id}`) jika ID dosen terisi.
- [ ] Jika API merespon dengan status sukses (**201** atau **200**), tutup form dan panggil ulang fungsi `muatData()` agar tabel memperbarui dirinya sendiri.
- [ ] Jika API merespon error validasi (status code **422**), tangkap properti `errors` dari respons JSON dan tampilkan ke elemen pesan error di bawah setiap kolom input yang sesuai.

### 3. Mengimplementasikan Fungsi Hapus Data (JavaScript DELETE)

- [ ] Tambahkan tombol **Hapus** pada setiap baris di tabel data (bersebelahan dengan tombol Detail).
- [ ] Buat fungsi JavaScript bernama `hapusData(id)`.
- [ ] Munculkan kotak dialog konfirmasi (`confirm()`) sebelum benar-benar menghapus data.
- [ ] Jika dikonfirmasi, kirim request `fetch()` dengan method **DELETE** ke URL `/api/dosen/{id}`.
- [ ] Setelah penghapusan berhasil, *refresh* tabel dengan memanggil kembali fungsi `muatData()`.

### 4. Penyempurnaan Tampilan (Opsional)

- [ ] Tampilkan fitur **alert notification** ketika aksi Tambah, Ubah, atau Hapus berhasil dieksekusi.
- [ ] Tambahkan fitur **Filter / Pencarian** menggunakan Query Parameter yang diproses pada API server dan dieksekusi melalui JavaScript.

> Gunakan materi dari implementasi halaman Mahasiswa di modul sebelumnya sebagai referensi Anda dalam menyusun struktur kode JavaScript di halaman Dosen ini.
