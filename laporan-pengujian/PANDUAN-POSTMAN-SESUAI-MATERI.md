# Panduan Uji Manual pakai Postman (Sesuai Materi P9)

Ini mengikuti **Bagian 4.2 materi**: *Uji Manual dengan Postman / Thunder Client*.

> Foto contoh di `captures/` **jangan dihapus** — itu cuma contoh. Capture tugas pakai hasil Postman milikmu.

---

## Persiapan (materi 4.1)

```powershell
php artisan serve
```

Base URL: `http://127.0.0.1:8000`  
Endpoint utama: `http://127.0.0.1:8000/api/mahasiswa`

---

## Setting Postman (wajib, sesuai materi)

Untuk **setiap** request:
1. Tambah header: `Accept` = `application/json`
2. Untuk **POST** dan **PUT**:
   - Body → **raw** → **JSON**
   - Header `Content-Type` = `application/json` (biasanya otomatis kalau Body raw JSON)

Lalu **Send** → screenshot hasil (method, URL, status, response).

---

## Tabel skenario (persis materi halaman 16)

| No | Skenario | Request | Hasil yang diharapkan |
|----|----------|---------|------------------------|
| 1 | Ambil daftar | `GET /api/mahasiswa` | **200**, ada `data`, `links`, `meta` |
| 2 | Cari | `GET /api/mahasiswa?search=andi` | **200**, hanya data yang cocok |
| 3 | Tambah valid | `POST /api/mahasiswa` + body JSON lengkap | **201**, message berhasil ditambahkan |
| 4 | Tambah tidak valid | `POST` nim duplikat / nama kosong | **422**, `errors` per kolom |
| 5 | Detail | `GET /api/mahasiswa/{id}` | **200**, satu data |
| 6 | Detail tidak ada | `GET /api/mahasiswa/99999` | **404**, `"Data tidak ditemukan."` |
| 7 | Ubah | `PUT /api/mahasiswa/{id}` + body JSON | **200**, data sudah berubah |
| 8 | Hapus | `DELETE /api/mahasiswa/{id}` | **200**, message berhasil dihapus |

---

## Isi tiap request di Postman

### 1) Ambil daftar
- Method: **GET**
- URL: `http://127.0.0.1:8000/api/mahasiswa`  
  (boleh juga `...?per_page=2` biar response pendek, seperti contoh materi)
- Headers: `Accept: application/json`
- Expected: **200** + `data` + `links` + `meta`

### 2) Cari
- Method: **GET**
- URL: `http://127.0.0.1:8000/api/mahasiswa?search=andi`
- Headers: `Accept: application/json`
- Expected: **200**, data Andi saja

### 3) Tambah valid (tambah data baru — wajib di tugas)
- Method: **POST**
- URL: `http://127.0.0.1:8000/api/mahasiswa`
- Headers: `Accept` + Body **raw → JSON**
- Body (format materi 2.4 / contoh curl materi):

```json
{
  "nim": "DEMO001",
  "nama": "Demo API",
  "prodi": "Sistem Informasi",
  "email": "demo@example.com",
  "nohp": "081234567890"
}
```

- Expected: **201 Created**
- Response mirip materi:

```json
{
  "data": { "id": ..., "nim": "DEMO001", "nama": "Demo API", ... },
  "message": "Data mahasiswa berhasil ditambahkan."
}
```

> **Catat `id`** dari response ini (dipakai skenario 5, 7, 8).

### 4) Tambah tidak valid
- Method: **POST**
- URL: `http://127.0.0.1:8000/api/mahasiswa`
- Body **raw → JSON** (sengaja salah, sesuai materi: nim duplikat + field kurang/salah):

```json
{
  "nim": "DEMO001",
  "email": "bukan-email"
}
```

- Expected: **422 Unprocessable Content**
- Ada `errors` per kolom (`nim`, `nama`, `prodi`, `email`), seperti materi halaman 9

### 5) Detail
- Method: **GET**
- URL: `http://127.0.0.1:8000/api/mahasiswa/{id}`  
  ganti `{id}` dengan id hasil skenario 3
- Expected: **200**, satu objek di `data`

### 6) Detail tidak ada
- Method: **GET**
- URL: `http://127.0.0.1:8000/api/mahasiswa/99999`
- Expected: **404**

```json
{ "message": "Data tidak ditemukan." }
```

### 7) Ubah
- Method: **PUT**
- URL: `http://127.0.0.1:8000/api/mahasiswa/{id}`
- Body **raw → JSON** (sesuai contoh curl materi):

```json
{
  "nim": "DEMO001",
  "nama": "Demo API Diubah",
  "prodi": "Sistem Informasi",
  "email": "demo@example.com",
  "nohp": "081234567890"
}
```

- Expected: **200**, nama sudah berubah + message berhasil diperbarui

### 8) Hapus
- Method: **DELETE**
- URL: `http://127.0.0.1:8000/api/mahasiswa/{id}`
- Headers: `Accept: application/json`
- Expected: **200**

```json
{ "message": "Data mahasiswa berhasil dihapus." }
```

---

## Yang di-screenshot (untuk Word)

Tiap skenario 1–8, foto harus kelihatan:
1. Method + URL
2. Status code (`200` / `201` / `422` / `404`)
3. Response JSON

Lalu di Word (file `2411501550_Raihan_Adi_Wijiyanto.docx`):
- tempel screenshot milikmu
- di bawahnya tulis penjelasan singkat (boleh salin dari Word yang sudah ada / materi)

---

## Checklist sesuai materi

- [ ] Server `php artisan serve` jalan
- [ ] Header `Accept: application/json` di semua request
- [ ] POST/PUT pakai Body → raw → JSON
- [ ] 8 skenario tabel materi sudah diuji
- [ ] Ada capture **tambah data baru** (skenario 3, status 201)
- [ ] Screenshot dimasukkan ke MS Word `NIM_Mahasiswa`
